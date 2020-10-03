<template>
 <div class="p-2 p-sm-3">
 <b-card no-body>
    <b-tabs card>
      <b-tab title="My Resources" active>
        <div class="resource-main">
          <b-row class="justify-content-end">
            <b-col cols="12" sm="5">
              <b-input-group>
                <b-form-input  placeholder="Search title" v-model="search"></b-form-input>
                <b-input-group-append>
                  <router-link to="/tutor/resource/create">
                    <div class="create-btn" >
                      <div class="btn btn-create" id="res">
                        <i class="fa fa-plus"></i> Resource
                      </div>
                    </div>
                  </router-link>
                  <b-tooltip triggers="hover" target="res">Add new resource</b-tooltip>
                </b-input-group-append>
              </b-input-group>
            </b-col>
          </b-row>

          <div class="container p-0">
            <div class="filter-table">
              <div class="filter-container">
                <div class="filter-btn mr-auto" @click="toggleFilter">
                  <span>Filter</span>
                  <i class="icon-sort"></i>
                </div>
              </div>
              <b-navbar toggleable="lg" type="dark" variant="info" v-if="filterShow">
                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

                <b-collapse id="nav-collapse" is-nav>
                  <!-- Right aligned nav items -->
                  <b-row v-if="tutor">
                    <b-col cols="12" sm="6">
                      <b-form-select size="sm" class="mr-3 toCaps" v-model="subject">
                        <b-form-select-option value disabled>-- Subject --</b-form-select-option>
                        <b-form-select-option value="all">-- All --</b-form-select-option>
                        <b-form-select-option
                          :value="item"
                          v-for="(item,idx) in JSON.parse(tutor.courses)"
                          :key="idx"
                        >{{item}}</b-form-select-option>
                      </b-form-select>
                    </b-col>
                    <b-col cols="12" sm="6">
                      <b-form-select size="sm" class="mr-3" v-model="myclass">
                        <b-form-select-option value disabled>-- Class --</b-form-select-option>
                        <b-form-select-option value="all">-- All --</b-form-select-option>
                        <b-form-select-option
                          :value="item"
                          v-for="(item,idx) in JSON.parse(tutor.levels)"
                          :key="idx"
                        >{{item}}</b-form-select-option>
                      </b-form-select>
                    </b-col>
                  </b-row>
                </b-collapse>
              </b-navbar>
            </div>
          </div>
          <div class="resource-table container bd-table">
            <div class="table-responsive rounded">
              <table class="table table-hover" v-if="sorted.length">
                <thead class="thead-lightblue">
                  <tr>
                    <th>S/N</th>
                    <th>CLASS</th>
                    <th>SUBJECT</th>
                    <th>TITLE</th>
                    <th>STATUS</th>

                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item,idx) in sorted" :key="idx">
                    <td scope="row">{{idx+1}}</td>
                    <td class="toCaps">{{item.level}}</td>
                    <td class="toCaps">{{item.subject}}</td>
                    <td class="toCaps">{{item.module}}</td>
                    <td class="toCaps">{{item.status}}</td>

                    <td class="options">
                      <div class :id="item.id.toString()">
                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>

                        <b-popover :target="item.id.toString()" triggers="hover" placement="top">
                          <ul>
                            <li @click="edit(item.id)">
                              <i class="fas fa-edit"></i> Edit
                            </li>
                            <li @click="view(item.syllabus_id)">
                              <i class="fa fa-eye" aria-hidden="true"></i> View
                            </li>
                            <li @click="drop(item.id)">
                              <i class="fas fa-minus-circle" aria-hidden="true"></i> Drop
                            </li>
                          </ul>
                        </b-popover>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              <b-alert v-else show>No data</b-alert>
            </div>
          </div>
        </div>
      </b-tab>
      <b-tab title="All Resources" v-if="tutor.head">
        <div class="resource-main">
          <b-modal id="view" size="xl" hide-footer title="Resource">
            <Preview :content="content" />
          </b-modal>
        
          <h5>{{tutor.head.class_name}}</h5>
          <b-row class="my-3">
            <b-col cols="12" sm="2">
              <b-form-group label="Subject">
                <b-form-select size="sm" v-model="subject">
                  <b-form-select-option value>Select</b-form-select-option>
                  <b-form-select-option
                    :value="item.name"
                    v-for="(item,idx) in subjects"
                    :key="idx"
                  >{{item.name}}</b-form-select-option>
                </b-form-select>
              </b-form-group>
            </b-col>
            <!-- <b-col cols="2">
          <b-form-group label="Teacher">
             <b-form-select v-model="mytutor">
              <b-form-select-option value="">Select</b-form-select-option>
            <b-form-select-option :value="item.id" v-for="(item,idx) in tutors"  :key="idx">
              {{item.name}}
            </b-form-select-option>
          </b-form-select>
          </b-form-group>
            </b-col>-->

            <b-col cols="12"  sm="5">
              <b-form-group label="Search ">
                <b-form-input size="sm" placeholder="Search" v-model="search"></b-form-input>
              </b-form-group>
            </b-col>
          </b-row>

          <div class="resource-table container bd-table">
            <div class="table-responsive rounded">
              <b-table-simple class="table table-hover table-bordered" v-if="sortedR.length">
                <b-thead class="thead-lightblue">
                  <b-tr>
                    <b-th>S/N</b-th>
                    <b-th>CLASS</b-th>
                    <b-th>SUBJECT</b-th>
                    <b-th>MODULE</b-th>
                    <b-th>STATUS</b-th>

                    <b-th></b-th>
                  </b-tr>
                </b-thead>
                <b-tbody>
                  <b-tr v-for="(item,idx) in sortedR" :key="idx">
                    <b-td scope="row">{{idx+1}}</b-td>
                    <b-td class="toCaps">{{item.level}}</b-td>
                    <b-td class="toCaps">{{item.subject}}</b-td>
                    <b-td class="toCaps">{{item.module}}</b-td>
                    <b-td class="toCaps">{{item.status}}</b-td>

                    <b-td class="options">
                      <div class :id="item.module.toString()">
                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>

                        <b-popover
                          :target="item.module.toString()"
                          triggers="hover"
                          placement="top"
                        >
                          <ul>
                            <li
                              v-if="item.status=='pending'"
                              @click="changeStatus(item.id,'approved')"
                            >
                              <i class="fas fa-edit"></i> Approve
                            </li>
                            <li
                              v-if="item.status=='approved'"
                              @click="changeStatus(item.id,'pending')"
                            >
                              <i class="fas fa-edit"></i> Unapprove
                            </li>

                            <li @click="viewall(item)">
                              <i class="fa fa-eye" aria-hidden="true"></i> View
                            </li>
                          </ul>
                        </b-popover>
                      </div>
                    </b-td>
                  </b-tr>
                </b-tbody>
              </b-table-simple>
               <b-alert v-else show>No data</b-alert>
            </div>
          </div>
        </div>
      </b-tab>
    </b-tabs>
  </b-card>
 </div>
</template>

<script>
import Preview from "./review";
export default {
  props: ["tutor"],
  data() {
    return {
      resources: [],
      allresources: [],
      subjects: [],
      classess: [],
      subject: "",
      myclass: "",
      term: "",
      filterShow: false,
      search: "",
      id: null,
      resources: [],
      search: "",
      level: "",
      subject: "",
      mytutor: "",
      type: "",
      subjects: [],
      tutors: [],
      levels: [],
      content:[],
      excerpt:'',
      module:''
    };
  },
  components: {
    Preview,
  },
  mounted() {
    this.getResources();
    this.getClasses();
    this.getSubjects();
    this.getAllResources();
  },

  computed: {
    sorted() {
      var search = this.resources.filter((item) =>
        item.module.toLowerCase().includes(this.search.toLowerCase())
      );

      var subject = search.filter((item) =>
        item.subject.toLowerCase().includes(this.subject.toLowerCase())
      );
      var level = subject.filter((item) =>
        item.level
          .trim()
          .toLowerCase()
          .includes(this.myclass.trim().toLowerCase())
      );
      return level.filter(item=>item.tutor_id== this.$props.tutor.id);
    },
    sortedR() {
      var search = this.resources.filter((item) =>
        item.module.toLowerCase().includes(this.search.toLowerCase())
      );

      var subject = search.filter((item) =>
        item.subject.toLowerCase().includes(this.subject.toLowerCase())
      );

      var level = subject.filter((item) =>
        item.level
          .toLowerCase()
          .includes(this.$props.tutor.head.class_name.toLowerCase())
      );

      var tutor = level.filter((item) =>
        item.tutor_id.toString().includes(this.mytutor.toString())
      );
      return tutor;
    },
  },
  methods: {
    changeStatus(id, val) {
      let data = {
        status: val,
      };
      axios
        .put(`/api/tutor-resourcess/${id}`, data, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.getResources();
          }
        });
    },

    viewall(content) {
      this.module = content.module
      this.excerpt = content.excerpt
      this.content = JSON.parse(content.content);
      this.$bvModal.show("view");
    },
    drop(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/tutor-resource/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.tutor.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getResources();
            }
          });
      }
    },

    getAllResources() {
      axios
        .get(`/api/tutor-resources`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.allresources = res.data;
          }
        });
    },
    getSubjects() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      axios
        .get("/api/tutor-all-subjects", {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.subjects = res.data;
          }
        })
        .catch();
    },
    getClasses() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      axios
        .get("/api/all-classes", {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.classess = res.data;
            // res.data.forEach((item) => {
            //   this.allClass.push(item.class_name);
            //   if (item.sub_class !== "") {
            //     item.sub_class.split(",").forEach((i) => {
            //       this.allClass.push(i);
            //     });
            //   }
            // });
          }
        });
    },
    toggleFilter() {
      this.filterShow = !this.filterShow;
    },
    sortContent(arr) {
      let ans = [];

      arr.forEach((item) => {
        ans.push(item.name);
      });

      return arr;
    },
    getResources() {
      axios
        .get(`/api/resource`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.resources = res.data;
          }
        });
    },

    edit(id) {
      this.$router.push(`/tutor/resource/edit/${id}`);
    },
    view(id) {
      this.$router.push(`/tutor/resource/view/${id}`);
    },
    drop(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/resource/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.tutor.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getResources();
            }
          });
      }
    },
    // multiDrop() {
    //   let del = confirm("Are you sure about this?");
    //   let data = {
    //     data: this.items
    //   };
    //   if (del) {
    //     axios
    //       .post("/api/multi-resource-drop", data, {
    //         headers: {
    //           Authorization: `Bearer ${this.$props.tutor.access_token}`
    //         }
    //       })
    //       .then(res => {
    //         if (res.status == 200) {
    //           this.getgroups();
    //           this.item = false
    //         }
    //       })
    //       .catch(err => {
    //         console.log("del -> err", err);
    //       });
    //   }
    // }
  },
};
</script>

<style scoped>
.resource-main {
  padding: 20px 40px;
}
.resource-btn {
  display: flex;
  justify-content: flex-end;
}
.filter-container {
  display: flex;
  justify-content: flex-end;
  padding-top: 10px;
}
.filter-btn {
  background: #fff;
  padding: 10px 20px;
  border-radius: 5px;
}
.filter-btn span {
  font-family: "Montserrat";
  font-weight: bold;
}
.filter-btn i {
  padding-left: 3px;
}
.btn-resource {
  background: #41cee2;
  box-shadow: 5px 4px 13px rgba(249, 247, 240, 0.25);
  border-radius: 10px;
  padding: 10px 20px;
  color: #fff;
}
.sort-section {
  display: flex;
  justify-content: space-between;
  background: #fff;
  border-radius: 10px;
}
.bg-info {
  background: #fff !important;
  box-shadow: 5px 4px 13px rgba(249, 247, 240, 0.25);
  margin-top: 20px;
  border-radius: 10px;
}

.nav-link {
  color: #000 !important;
}

.resource-table {
  padding-top: 20px;
}
.table {
  border-radius: 10px;
}

.search {
  width: 250px;
  border-color: #41cee2;
}

.rounded-pill {
  border-radius: 50rem !important;
}
.hiden {
  opacity: 0;
}

.options {
  position: relative;
  width: 10px;
}
.option-box {
  position: absolute;
  bottom: 100%;
  background: white;
  padding: 15px;
  font-size: 14px;
  width: 100px;
  display: none;
  right: 10px;
}
.options:hover .option-box {
  display: block;
}
.option-box ul {
  text-decoration: none;
  list-style: none;
}
.option-box ul li {
  padding: 5px 0;
  cursor: pointer;
}
.fa-ellipsis-v {
  cursor: pointer;
}
.create-btn {
  display: flex;
  justify-content: flex-end;
}
/* .btn {
  padding: 10px 20px;
  border-radius: 5px;
} */
.btn-create {
  background: #56cee3;
  color: #fff;
}
.search {
  width: 250px;
  border-color: #41cee2;
}
ul {
  list-style: none;
}
li {
  margin-bottom: 1rem;
}
@media screen and (max-width: 600px) {
  .resource-main {
    padding: 20px 0 40px;
  }
}
</style>