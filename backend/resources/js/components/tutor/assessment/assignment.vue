<template>
  <div class="body">
    <b-row>
      <b-col>
        <h3>Assignments</h3>
      </b-col>
    </b-row>
    <b-row class="justify-content-end">
      <b-col cols="12" sm="4">
        <b-input-group>
          <b-form-input v-model="search" placeholder="Search title"></b-form-input>
          <b-input-group-append>
            <router-link to="/tutor/assessment/create/assignment">
              <div class="create-btn">
                <div class="btn btn-create" id="assignment">
                  <b-icon icon="plus" size="1rem"></b-icon>Assignment
                </div>
                <b-tooltip target="assignment"  triggers="hover">Create new assignment</b-tooltip>
              </div>
            </router-link>
          </b-input-group-append>
        </b-input-group>
      </b-col>
    </b-row>
    <b-container>
      <div class="filter-table">
        <div class="filter-container">
          <div class="filter-btn btn" @click="toggleFilter">
            <span>Filter</span>
            <i class="icon-sort"></i>
          </div>
        </div>
        <b-navbar toggleable="lg" type="dark" variant="info" v-if="filterShow">
          <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

          <b-collapse id="nav-collapse" is-nav>
            <!-- Right aligned nav items -->
            <b-navbar-nav class>
              <b-form-select class="mr-3" v-model="myclass">
                <b-form-select-option value="" disabled>-- Select --</b-form-select-option>
              
                <b-form-select-option
                  :value="item"
                  v-for="(item,idx) in JSON.parse(tutor.levels)"
                  :key="idx"
                >{{item}}</b-form-select-option>
              </b-form-select>
            </b-navbar-nav>
          </b-collapse>
        </b-navbar>
      </div>
      <b-row>
        <b-col>
          <div class="bd-table">
            <b-table responsive :fields="fields" :items="items">
              <template v-slot:cell(action)="data">
                <div class="" :id="data.item.id.toString()">
                  <i class="fa fa-ellipsis-v" aria-hidden="true" ></i>
                
                    <b-popover :target="data.item.id.toString()" triggers="hover" placement="top">
                      <ul>
                        <li>
                          <span
                            @click="publish(true,data.item.id)"
                            v-if="data.item.publish_status== false"
                          >Publish</span>
                          <span @click="publish(false,data.item.id)" v-else>Unpublish</span>
                        </li>
                        <li @click="view(data.item)">View</li>
                        <li @click="edit(data.item.id)">Edit</li>
                        <li @click="drop(data.item.id)">Drop</li>
                      </ul>
                    </b-popover>
                 
                </div>
              </template>
              <template v-slot:cell(title)="data">
                <div class="main-title"  v-if="data.item.option">
                  <div class="title">{{JSON.parse(data.item.option).title}}</div>
                  <div class="text-muted">{{JSON.parse(data.item.option).description}}</div>
                </div>
              </template>
              <template v-slot:cell(publish_status)="data">
                <span v-if="data.item.publish_status== true">Published</span>
                <span v-else>Unpublished</span>
              </template>
              <template
                v-slot:cell(created_at)="data"
              >{{data.item.created_at | moment('MMM D YYYY')}}</template>
              <template v-slot:cell(Sn)="data">{{data.index+1}}</template>
            </b-table>
          </div>
        </b-col>
      </b-row>
    </b-container>
    <b-modal id="prev" title="Preview" size="lg" hide-footer>
      <Preview :options="options" :form="form" />
    </b-modal>
  </div>
</template>

<script>
import Preview from "./preview";
export default {
  props:['tutor'],
  data() {
    return {
      search: "",
      myclass:'',
      fields: [
        { key: "title", sortable: true },
        "session",
        { key: "subject", sortable: true },

        "level",
        "publish_status",
        "status",
        "action",
      ],
      items: [],
      filterShow: false,
      form: [],
      overall: null,
      options: {
        title: "",
        subject: "",
        level: "",
        type: "",
        duration: {
          start_time: "",
          end_time: "",
        },
      },
    };
  },
  components: {
    Preview,
  },
  mounted() {
    this.getData();
  },
  computed: {
    sorted() {
      return this.items.filter((i) => {
        return i.title.toLowerCase().includes(this.search.toLowerCase());
      });
    },
  },
  methods: {
    view(content) {
      this.overall = content.overall;

      this.options.title = content.title;
      this.options.subject = content.subject;
      this.options.myclass = content.level;
      this.options.session = content.session;
      this.options.type = content.type;
      this.options.duration.start_time = content.start;
      this.options.duration.end_time = content.end;
      this.form = JSON.parse(content.assessment);

      this.$bvModal.show("prev");
    },
    publish(name, id) {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      let data = {
        stat: name,
      };
      axios
        .put(`/api/publish-assessment/${id}`, data, {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.getData();
          }
        });
    },
    drop(id) {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/assessment/${id}`, {
            headers: {
              Authorization: `Bearer ${tutor.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getData();
            }
          });
      }
    },
    toggleFilter() {
      this.filterShow = !this.filterShow;
    },
    getData() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      axios
        .get(`/api/assessment/assignment`, {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.items = res.data;
          }
        })
        .catch();
    },

    edit(id) {
      this.$router.push(`/tutor/assessment/edit/${id}`);
    },
  },
};
</script>

<style scoped>
.body {
  font-family: "Montserrat";
}
.main-title {
  width: 350px;
}
.title {
  font-weight: bold;
  color: #56cee3;
}
.options {
  position: relative;
}
.option {
  display: none;
  position: absolute;
  padding: 15px;
  bottom: 80%;

  right: 12px;
  background: white;
}
.options:hover .option {
  display: block;
}
.option li {
  padding: 4px 15px;
  cursor: pointer;
}
ul {
  list-style: none;
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

.rounded-pill {
  border-radius: 50rem !important;
}
.filter-container {
  display: flex;
  justify-content: flex-start;
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
@media (max-width: 600px) {
  .main-title {
    width: 250px;
  }
}
</style>