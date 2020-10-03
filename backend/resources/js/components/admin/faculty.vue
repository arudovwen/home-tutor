<template>
  <div class="p-3">
    <b-modal id="faculty" title="Add Faculty" hide-footer>
      <b-form @submit.prevent="addFaculty">
        <b-form-group label="Faculty name">
          <b-form-input v-model="faculty.name" placeholder="Faculty name"></b-form-input>
        </b-form-group>
        <!-- <b-form-group label="Faculty Head">
          <b-form-input type="text" v-model="faculty.head" placeholder="Head of Faculty"></b-form-input>
        </b-form-group> -->

        <b-form-group class="my-3 text-center">
          <b-button size="sm" type="submit" v-if="!type">Add</b-button>
          <b-button size="sm" @click="updateFaculty" v-else type="button">Update</b-button>
        </b-form-group>
      </b-form>
    </b-modal>
    <b-modal id="faculty-edit" title="Update Faculty" hide-footer>
      <b-form @submit.prevent="updateFaculty">
        <b-form-group label="Faculty name">
          <b-form-input v-model="faculty.name" placeholder="Faculty name"></b-form-input>
        </b-form-group>

        <!-- <b-form-group label="Faculty Head">
          <b-form-input type="text" v-model="faculty.head" placeholder="Head of Faculty"></b-form-input>
        </b-form-group> -->

        <b-form-group class="my-3 text-center">
          <b-button size="sm" type="submit">Update</b-button>
        </b-form-group>
      </b-form>
    </b-modal>
    <b-modal id="department" title="Add Department" hide-footer>
      <b-form @submit.prevent="addDepartment">
        <b-form-group label="Faculty">
          <b-form-select v-model="department.faculty">
            <b-form-select-option value>Select</b-form-select-option>
            <b-form-select-option
              :value="faculty.id"
              v-for="(faculty,idx)  in faculties"
              :key="idx"
            >{{faculty.name}}</b-form-select-option>
          </b-form-select>
        </b-form-group>
        <b-form-group label="Department Name">
          <b-form-input v-model="department.name"></b-form-input>
        </b-form-group>
        <!-- <b-form-group label="Head of Department">
          <b-form-select v-model="department.head">
            <b-form-select-option value>Choose</b-form-select-option>
            <b-form-select-option
              :value="tutor.name"
              v-for="(tutor,idx)  in tutors"
              :key="idx"
            >{{tutor.name}}</b-form-select-option>
          </b-form-select>
        </b-form-group> -->

        <b-form-group class="my-3 text-center">
          <b-button size="sm" type="submit">Add</b-button>
        </b-form-group>
      </b-form>
    </b-modal>

    <b-modal id="department-edit" title="Update Department" hide-footer>
      <b-form @submit.prevent="updateDepartment">
        <b-form-group label="Faculty">
          <b-form-select v-model="department.faculty">
            <b-form-select-option value>Select</b-form-select-option>
            <b-form-select-option
              :value="faculty.id"
              v-for="(faculty,idx)  in faculties"
              :key="idx"
            >{{faculty.name}}</b-form-select-option>
          </b-form-select>
        </b-form-group>
        <b-form-group label="Name">
          <b-form-input v-model="department.name"></b-form-input>
        </b-form-group>
        <!-- <b-form-group label="Head of Department">
          <b-form-select v-model="department.head">
            <b-form-select-option value>Choose</b-form-select-option>
            <b-form-select-option
              :value="tutor.name"
              v-for="(tutor,idx)  in tutors"
              :key="idx"
            >{{tutor.name}}</b-form-select-option>
          </b-form-select>
        </b-form-group> -->

        <b-form-group class="my-3 text-center">
          <b-button size="sm" type="submit">Update</b-button>
        </b-form-group>
      </b-form>
    </b-modal>

    <b-modal id="course" title="Add course" hide-footer>
      <b-form @submit.prevent="addCourse">
        <b-form-group label="Faculty">
          <b-form-select v-model="course.faculty_id">
            <b-form-select-option value>Select</b-form-select-option>
            <b-form-select-option
              :value="faculty.id"
              v-for="(faculty,idx)  in faculties"
              :key="idx"
            >{{faculty.name}}</b-form-select-option>
          </b-form-select>
        </b-form-group>

        <b-form-group label="Department">
          <b-form-select v-model="course.department_id" v-if="mycourses.length">
            <b-form-select-option value>Select</b-form-select-option>
            <b-form-select-option
              :value="department.id"
              v-for="(department,idx)  in mycourses[0].departments"
              :key="idx"
            >{{department.name}}</b-form-select-option>
          </b-form-select>
        </b-form-group>
        <b-form-group label="Name">
          <b-form-input v-model="course.name"></b-form-input>
        </b-form-group>

        <b-form-group class="my-3 text-center">
          <b-button size="sm" type="submit">Add</b-button>
        </b-form-group>
      </b-form>
    </b-modal>

    <b-modal id="course-edit" title="Update Course" hide-footer>
      <b-form @submit.prevent="updateCourse">
        <b-form-group label="Faculty">
          <b-form-select v-model="course.faculty_id">
            <b-form-select-option value>Select</b-form-select-option>
            <b-form-select-option
              :value="faculty.id"
              v-for="(faculty,idx)  in faculties"
              :key="idx"
            >{{faculty.name}}</b-form-select-option>
          </b-form-select>
        </b-form-group>
        <b-form-group label="Department">
          <b-form-select v-model="course.department_id" v-if="mycourses.length">
            <b-form-select-option value>Select</b-form-select-option>
            <b-form-select-option
              :value="department.id"
              v-for="(department,idx)  in mycourses[0].departments"
              :key="idx"
            >{{department.name}}</b-form-select-option>
          </b-form-select>
        </b-form-group>
        <b-form-group label="Name">
          <b-form-input v-model="course.name"></b-form-input>
        </b-form-group>

        <b-form-group class="my-3 text-center">
          <b-button size="sm" type="submit">Update</b-button>
        </b-form-group>
      </b-form>
    </b-modal>
    <b-card no-body class="full-body myfac">
      <b-tabs  pills card  :vertical="!mobile" justified :end="!mobile" class="full-body ">
        <b-tab title="Faculties" active class="">
          <nav class="mb-4">
            <b-button size="sm" id="addfaculty" v-b-modal.faculty><b-icon icon="plus-circle-fill" style="width:14px" font-scale="1"></b-icon>  Add Faculty</b-button>
          </nav>
          <b-tooltip target="addfaculty" triggers="hover">Add a new faculty</b-tooltip>
          <b-table bordered :items="faculties" :fields="facultyFields">
            <template v-slot:cell(sn)="data">
              <div>{{data.index+1}}</div>
            </template>
            
            <template v-slot:cell(action)="data">
              <div class="d-flex justify-content-lg-around">
                <div @click="editFaculty(data.item)">Edit</div>
                <div @click="dropFaculty(data.item.id)">Drop</div>
              </div>
            </template>
          </b-table>
        </b-tab>
        <b-tab title="Departments">
          <nav class="mb-4">
            <b-button size="sm" id="addept" v-b-modal.department><b-icon icon="plus-circle-fill" style="width:14px" font-scale="1"></b-icon>  Add Department</b-button>
          </nav>
          <b-tooltip target="addept" triggers="hover">Add a new department</b-tooltip>
          <b-row>
            <b-col class="p-0" cols="3">
              <b-form-group label="Faculty">
                <b-form-select v-model="myfaculty">
                  <b-form-select-option value>Select</b-form-select-option>
                  <b-form-select-option
                    :value="faculty.id"
                    v-for="(faculty,idx)  in faculties"
                    :key="idx"
                  >{{faculty.name}}</b-form-select-option>
                </b-form-select>
              </b-form-group>
            </b-col>
          </b-row>
          <b-table bordered :items="mydepartments" :fields="DeptFields">
            <template v-slot:cell(sn)="data">
              <div>{{data.index+1}}</div>
            </template>
       
            <template v-slot:cell(action)="data">
              <div class="d-flex justify-content-lg-around">
                <div @click="editDepartment(data.item)">Edit</div>
                <div @click="dropDepartment(data.item.id)">Drop</div>
              </div>
            </template>
          </b-table>
        </b-tab>
        <b-tab title="Programmes">
          <nav class="mb-4">
            <b-button size="sm" id="addprogram" v-b-modal.course><b-icon icon="plus-circle-fill" style="width:14px" font-scale="1"></b-icon>  Add Programme</b-button>
          </nav>
          <b-tooltip target="addprogram" triggers="hover">Add a new programme</b-tooltip>
          <b-row>
            <b-col cols="3" class="p-0">
              <b-form-group label="Faculty">
                <b-form-select v-model="myfaculty">
                  <b-form-select-option :value="null">Select</b-form-select-option>
                  <b-form-select-option
                    :value="faculty.id"
                    v-for="(faculty,idx)  in faculties"
                    :key="idx"
                  >{{faculty.name}}</b-form-select-option>
                </b-form-select>
              </b-form-group>
            </b-col>
            <b-col cols="3">
              <b-form-group label="Department">
                <b-form-select v-model="mycourse_id" v-if="sortedDept.length">
                  <b-form-select-option :value="null">Select</b-form-select-option>
                  <b-form-select-option
                    :value="department.id"
                    v-for="(department,idx)  in sortedDept[0].departments"
                    :key="idx"
                  >{{department.name}}</b-form-select-option>
                </b-form-select>
              </b-form-group>
            </b-col>
          </b-row>

          <div v-if="sortCourse.length">
            <b-table bordered :items="sortCourse" :fields="courseFields">
              <template v-slot:cell(sn)="data">
                <div>{{data.index+1}}</div>
              </template>
              <template v-slot:cell(action)="data">
                <div class="d-flex justify-content-lg-around">
                  <div @click="editCourse(data.item)">Edit</div>
                  <div @click="dropCourse(data.item.id)">Drop</div>
                </div>
              </template>
            </b-table>
          </div>

          <b-alert :show="!sortCourse.length">Choose a department</b-alert>
        </b-tab>
      </b-tabs>
    </b-card>
  </div>
</template>

<script>
export default {
  props: ["admin"],
  data() {
    return {
      faculties: [],
      departments: [],
      department: {
        name: "",
        head: "none",
      },
      courses: [],
      course: {
        name: "",
        faculty_id: null,
        department_id: null,
      },
      faculty: {
        name: "",
        head: "none",
      },
      tutors: [],
      facultyFields: ["sn", "name", "action"],
      DeptFields: ["sn", "name", "action"],
      courseFields: ["sn", "name", "action"],
      type: false,
      myfaculty: null,
      mycourse_id: null,
       mobile:false,
    };
  },
    created(){
    if (window.innerWidth < 768) {
      this.mobile = true
    }else{
       this.mobile = false
    }
  },
  mounted() {
    this.getFaculties();
    this.getDepartment();
    this.getCourses();
    this.getTutors();
  },
  computed: {
    mycourses() {
      return this.faculties.filter((item) => item.id == this.course.faculty_id);
    },
    sortedDept() {
      return this.faculties.filter((item) => item.id == this.myfaculty);
    },
    mydepartments() {
      return this.departments.filter(
        (item) => item.faculty_id == this.myfaculty
      );
    },
    sortCourse() {
      return this.courses.filter(
        (item) => item.department_id == this.mycourse_id
      );
    },
  },
  methods: {
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
            this.busy = false;
          }
        });
    },
    getFaculties() {
      axios
        .get(`/api/faculty`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.faculties = res.data;
            this.myfaculty = res.data[0].id;
          }
        });
    },
    addFaculty() {
      axios
        .post(`/api/faculty`, this.faculty, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.faculties.push(res.data);
            this.faculty = {
              name: "",
              head: "",
            };
            this.$bvModal.hide("faculty");
          }
        });
    },
    editFaculty(item) {
      this.id = item.id;
      this.faculty = item;
      this.type = true;
      this.$bvModal.show("faculty-edit");
    },
    updateFaculty() {
      axios
        .put(`/api/faculty/${this.id}`, this.faculty, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.getFaculties();
            this.type = false;
            this.$bvModal.hide("faculty");
            this.faculty = {
              name: "",
              head: "",
            };
          }
        });
    },
    dropFaculty(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/faculty/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getFaculties();
            }
          });
      }
    },

    getDepartment() {
      axios
        .get(`/api/department`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.departments = res.data;
          }
        });
    },
    addDepartment() {
      axios
        .post(`/api/department`, this.department, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.departments.push(res.data);
            this.getFaculties()
            this.department = {
              name: "",
              head: "",
            };
            this.$bvModal.hide("department");
          }
        });
    },
    editDepartment(item) {
      this.id = item.id;
      this.department = item;
      this.department.faculty = item.faculty_id;
      this.type = true;
      this.$bvModal.show("department-edit");
    },
    updateDepartment() {
      axios
        .put(`/api/department/${this.id}`, this.department, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.getDepartment();
            this.type = false;
            this.$bvModal.hide("department-edit");
            this.department = {
              name: "",
            };
          }
        });
    },
    dropDepartment(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/department/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getDepartment();
            }
          });
      }
    },

    getCourses() {
      axios
        .get(`/api/course`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.courses = res.data;
          }
        });
    },
    addCourse() {
      axios
        .post(`/api/course`, this.course, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.courses.push(res.data);
            this.course = {
              name: "",
            };
            this.$bvModal.hide("course");
          }
        });
    },
    editCourse(item) {
      this.id = item.id;
      this.course = item;
      this.course.department = item.department_id;
      this.type = true;
      this.$bvModal.show("course-edit");
    },
    updateCourse() {
      axios
        .put(`/api/course/${this.id}`, this.course, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.getCourses();
            this.type = false;
            this.$bvModal.hide("course-edit");
            this.course = {
              name: "",
              head: "",
            };
          }
        });
    },
    dropCourse(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/course/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getCourses();
            }
          });
      }
    },
  },
};
</script>

<style scoped>

.form-group {
  margin-bottom: 0.8rem;
}
.nav-pills .nav-link.active, .nav-pills .show > .nav-link {
    color: #fff;
    background-color: green;
}
</style>