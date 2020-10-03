<template>
  <div class="resource-main">
    <b-modal id="view" size="xl" hide-footer title="Resource">
      <Preview :id="id" :admin="admin" />
    </b-modal>
    <h5>Resources</h5>
    <b-row class="my-3">
      <b-col md="2">
        <b-form-group
          :label="
            JSON.parse(admin.level).includes('tertiary') ? 'Course' : 'Subject'"
        >
          <b-form-select size="sm" v-model="subject">
            <b-form-select-option value="">Select</b-form-select-option>
            <b-form-select-option
              :value="item.name"
              v-for="(item, idx) in subjects"
              :key="idx"
            >
              {{ item.name }}
            </b-form-select-option>
          </b-form-select>
        </b-form-group>
      </b-col>
      <b-col md="2">
        <b-form-group
          :label="
            JSON.parse(admin.level).includes('tertiary')
              ? 'Lecturer'
              : 'Teacher'
          "
        >
          <b-form-select  size="sm" v-model="tutor">
            <b-form-select-option value="">Select</b-form-select-option>
            <b-form-select-option
              :value="item.id"
              v-for="(item, idx) in tutors"
              :key="idx"
            >
              {{ item.name }}
            </b-form-select-option>
          </b-form-select>
        </b-form-group>
      </b-col>
      <b-col md="2">
        <b-form-group
          :label="
            JSON.parse(admin.level).includes('tertiary') ? 'Level' : 'Class'
          "
        >
          <b-form-select  size="sm" v-model="level">
            <b-form-select-option value="">Select</b-form-select-option>
            <b-form-select-option
              :value="item.class_name"
              v-for="(item, idx) in levels"
              :key="idx"
            >
              {{ item.class_name }}
            </b-form-select-option>
          </b-form-select>
        </b-form-group>
      </b-col>

      <b-col md="4">
        <b-form-group label="Search ">
          <b-form-input  size="sm" placeholder="Search" v-model="search"></b-form-input>
        </b-form-group>
      </b-col>
    </b-row>

    <div class="resource-table container bd-table">
      <div class="table-responsive rounded">
        <b-table-simple class="table table-hover" v-if="sorted.length">
          <b-thead class="thead-lightgreen">
            <b-tr>
              <b-th>S/N</b-th>
              <b-th>{{
                JSON.parse(admin.level).includes("tertiary") ? "LEVEL" : "CLASS"
              }}</b-th>
              <b-th>{{
                JSON.parse(admin.level).includes("tertiary")
                  ? "COURSE"
                  : "SUBJECT"
              }}</b-th>
              <b-th>MODULE</b-th>
              <b-th>STATUS</b-th>

              <b-th></b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <b-tr v-for="(item, idx) in sorted" :key="idx">
              <b-td scope="row">{{ idx + 1 }}</b-td>
              <b-td class="toCaps">{{ item.level }}</b-td>
              <b-td class="toCaps">{{ item.subject }}</b-td>
              <b-td class="toCaps">{{ item.module }}</b-td>
              <b-td class="toCaps">{{ item.status }}</b-td>

              <b-td class="options">
                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                <div class="option-box">
                  <ul>
                    <li
                      v-if="item.status == 'pending'"
                      @click="changeStatus(item.id, 'approved')"
                    >
                      <i class="fas fa-edit"></i> Approve
                    </li>
                    <li
                      v-if="item.status == 'approved'"
                      @click="changeStatus(item.id, 'pending')"
                    >
                      <i class="fas fa-edit"></i> Unapprove
                    </li>

                    <li @click="view(item.syllabus_id)">
                      <i class="fa fa-eye" aria-hidden="true"></i> View
                    </li>
                  </ul>
                </div>
              </b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
          <b-alert show v-else>No Data</b-alert>
      </div>
    </div>
  </div>
</template>

<script>
import Preview from "./viewResource";
export default {
  props: ["admin"],
  data() {
    return {
      id: null,
      resources: [],
      search: "",
      level: "",
      subject: "",
      tutor: "",
      type: "",
      subjects: [],
      tutors: [],
      levels: [],
    };
  },

  components: {
    Preview,
  },
  mounted() {
    this.getResources();

    this.getSubjects();
    this.getTutors();
    this.getclasses();
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
        item.level.toLowerCase().includes(this.level.toLowerCase())
      );

      var tutor = level.filter((item) =>
        item.tutor_id.toString().includes(this.tutor.toString())
      );
      return tutor;
    },
  },
  methods: {
    getSubjects() {
      axios
        .get("/api/subject", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.subjects = res.data;
          }
        });
    },
    getTutors() {
      axios
        .get("/api/tutor", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.tutors = res.data;
          }
        });
    },
    getclasses() {
      axios
        .get("/api/classes", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.levels = res.data;
          }
        });
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
        .get(`/api/admin-resources`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.resources = res.data;
          }
        });
    },
    changeStatus(id, val) {
      let data = {
        status: val,
      };
      axios
        .put(`/api/admin-resources/${id}`, data, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.getResources();
          }
        });
    },
    edit(id) {
      this.$router.push(`/admin/resource/edit/${id}`);
    },
    view(id) {
      this.id = id;
      this.$bvModal.show("view");
    },
    drop(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/resource/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getResources();
            }
          });
      }
    },
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
  background: #008e3a;
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
  border-color: #008e3a;
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
  bottom: 0;
  background: white;
  padding: 15px;
  font-size: 12px;
  width: 120px;
  display: none;
  right: 30px;
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
ul {
  margin: 0;
}
.btn-create {
  background: #008e3a;
  color: #fff;
}
.search {
  width: 250px;
  border-color: #008e3a;
}
.form-group {
  margin: 15px 0;
}
@media screen and (max-width: 600px) {
  .resource-main {
    padding: 20px 15px 40px;
  }
  legend{
    font-size:14px !important;
  }
}
</style>