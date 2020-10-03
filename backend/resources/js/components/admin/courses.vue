<template>
  
  <div class="main-body">
    <div class="subject">
      <div class="container">
        <div class="row">
          <div class="col-md-9 board">
            <div class="announcement-board">
              <div class="announcement-board-content">
                <div class="announcement-title">
                  <h5>Announcement Board</h5>
                <div  v-if="notify.length">
                    <swiper class="swiper" >
                    <swiper-slide v-if="notify.length">
                      <p>{{notify[0].message}}</p>
                    </swiper-slide>
                     <swiper-slide v-if="notify.length > 1">
                      <p>{{notify[1].message}}</p>
                    </swiper-slide>
                     <swiper-slide v-if="notify.length > 2">
                      <p>{{notify[2].message}}</p>
                    </swiper-slide>
                     <swiper-slide v-if="notify.length > 3">
                      <p>{{notify[3].message}}</p>
                    </swiper-slide>
                     <swiper-slide v-if="notify.length > 4">
                      <p>{{notify[4].message}}</p>
                    </swiper-slide>
                     <swiper-slide v-if="notify.length > 5">
                      <p>{{notify[5].message}}</p>
                    </swiper-slide>

                  </swiper>
                </div>
                  <b-alert show v-else>No Announcemnt</b-alert>
                </div>
                <img src="/images/announcement.png" alt />
              </div>
            </div>
            <div class="subject-table">
              <b-row>
                <b-col class="p-0" cols="12" sm="3">
                      <b-form-group label="Category">
                       <b-form-select v-model="category" class="toCaps">
                         <b-form-select-option value="">All</b-form-select-option>
                      <b-form-select-option
                        :value="level"
                        v-for="(level, idx) in JSON.parse(admin.level)"
                        :key="idx"
                      >
                        <span>{{ level }}</span>
                      </b-form-select-option>
                    </b-form-select>
                    </b-form-group>
                </b-col>
              </b-row>
              <b-table
                :items="sortSubject"
                :fields="fields"
                hover
                striped
                responsive
                v-if="!JSON.parse(admin.level).includes('tertiary')"
              >
                <template v-slot:cell(action)="data">
                  <div class="options">
                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                    <div class="option shadow">
                      <ul>
                        <li>
                          <span @click="edit(data.item.id)">
                            <i class="fas fa-edit"></i>Edit
                          </span>
                        </li>
                        <li>
                          <span class="mr-3" @click="drop(data.item.id)">
                            <i class="fa fa-minus-circle" aria-hidden="true"></i> Drop
                          </span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </template>

                <template v-slot:cell(drop)="data">
                  <b-form-checkbox :value="data.item.id" v-model="items"></b-form-checkbox>
                </template>
                <template v-slot:cell(subject)="data"><div class="toCaps">{{data.item.name}}</div></template>
              </b-table>

              <b-table :items="sortSubject" :fields="tertfields" hover striped v-else responsive>
                <template v-slot:cell(action)="data">
                  <div class="options">
                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                    <div class="option shadow">
                      <ul>
                        <li>
                          <span @click="edit(data.item.id)">
                            <i class="fas fa-edit"></i>Edit
                          </span>
                        </li>
                        <li>
                          <span class="mr-3" @click="drop(data.item.id)">
                            <i class="fa fa-minus-circle" aria-hidden="true"></i> Drop
                          </span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </template>

                <template v-slot:cell(drop)="data">
                  <b-form-checkbox :value="data.item.id" v-model="items"></b-form-checkbox>
                </template>
                <template v-slot:cell(subject)="data">
                <div class="toCaps">  {{data.item.name.toUpperCase()}}</div></template>
              </b-table>
            </div>
          </div>
          <div class="col-md-3">
            <div class="add-subject">
              <div class="add-subject-btn">
                <b-button v-b-modal.modal-1 class="mb-3">Add Subject</b-button>
                <!-- <b-button
                  v-b-modal.grade
                  v-if="JSON.parse(admin.level).includes('tertiary')"
                >Add Grade</b-button> -->

                <b-modal id="modal-1" size="sm" title="Add Subject" hide-footer>
                  <b-form @submit.prevent="addSubjects">
                    <b-form-group label="Category">
                       <b-form-select v-model="subject.category" class="toCaps">
                      <b-form-select-option
                        :value="level"
                        v-for="(level, idx) in JSON.parse(admin.level)"
                        :key="idx"
                      >
                        <span>{{ level }}</span>
                      </b-form-select-option>
                    </b-form-select>
                    </b-form-group>
                    <b-form-group
                      label="Subject name"
                    >
                      <b-form-input
                        required
                        type="text"
                        v-model="subject.name"
                        aria-describedby="helpId"
                        placeholder="Enter subject name"
                      />
                    </b-form-group>
                    <b-form-group
                      :label="subject.category.toLowerCase()=='tertiary'?'Course title':'Subject code'"
                    >
                      <b-form-input
                        required
                        type="text"
                        v-model="subject.code"
                        aria-describedby="helpId"
                        :placeholder="subject.category.toLowerCase()=='tertiary'?'Enter course title':'Enter subject code'"
                      />
                    </b-form-group>
                    <b-form-group
                      label="Department"
                      v-if="subject.category.toLowerCase()=='tertiary'"
                    >
                      <div class="search-box">
                        <b-form-input
                          size="sm"
                          placeholder="Search"
                          class="mb-2"
                          v-model="searchCourse"
                        ></b-form-input>
                        <b-row class="box" >
                          <b-col cols="6">
                            <b-form-radio size="sm" :value="0" v-model="subject.course">All</b-form-radio>
                          </b-col>
                          <b-col cols="6" v-for="(course,idx)  in sortCourse" :key="idx">
                            <b-form-radio
                              size="sm"
                              :value="course.id"
                              v-model="subject.course"
                            >{{course.name}}</b-form-radio>
                          </b-col>
                        </b-row>
                      </div>
                    </b-form-group>

                    <!-- <b-form-group label="Assign Teacher">
                      <div class="search-box">
                        <b-form-input
                          size="sm"
                          placeholder="Search"
                          class="mb-2"
                          v-model="searchTeacher"
                        ></b-form-input>

                        <b-row class="box" v-if="searchTeacher !==''">
                          <b-col cols="6" v-for="(item,idx)  in sortTeacher" :key="idx">
                            <b-form-checkbox
                              size="sm"
                              :value="item.id"
                              v-model="subject.teacher"
                            >{{item.name}}</b-form-checkbox>
                          </b-col>
                        </b-row>
                      </div>
                    </b-form-group> -->
                    <b-form-group
                      label="Semester"
                      v-if="subject.category.toLowerCase()=='tertiary'"
                    >
                      <b-form-select required v-model="subject.session">
                        <b-form-select-option value="first">First</b-form-select-option>
                        <b-form-select-option value="second">Second</b-form-select-option>
                      </b-form-select>
                    </b-form-group>
                    <b-form-group
                      label="Subject Line"
                      v-if="subject.category.toLowerCase()=='secondary'"
                    >
                      <b-form-select v-model="subject.line">
                        <b-form-select-option value="general">General</b-form-select-option>
                        <b-form-select-option value="science">Science</b-form-select-option>
                        <b-form-select-option value="art">Art</b-form-select-option>
                        <b-form-select-option value="commercial">Commercial</b-form-select-option>
                      </b-form-select>
                    </b-form-group>
                    <b-form-group
                      label="Course credit"
                      v-if="subject.category.toLowerCase()=='tertiary'"
                    >
                      <b-form-input
                        required
                        type="number"
                        v-model="subject.credit"
                        aria-describedby="helpId"
                        placeholder="3"
                      />
                    </b-form-group>
                    <b-form-group>
                      <b-button
                        v-if="!update"
                        type="submit"
                        class="btn btn-primary btn-block"
                      >Create</b-button>
                      <div v-else class="w-100">
                        <b-form-row>
                          <b-col>
                            <b-button type="button" block @click="updateN">Update</b-button>
                          </b-col>
                          <b-col>
                            <b-button
                              type="button"
                              block
                              variant="outline-secondary"
                              @click="cancel"
                            >Cancel</b-button>
                          </b-col>
                        </b-form-row>
                      </div>
                    </b-form-group>
                  </b-form>
                </b-modal>
                <b-modal id="grade" scrollable hide-footer>
                  <div class>
                    <form @submit.prevent="addGrade" class="p-3">
                      <h5 class="text-center">Add Grade (Tertiary institutions)</h5>

                      <b-form-group label="Grade">
                        <b-form-input
                          required
                          type="text"
                          v-model="grade.name"
                          aria-describedby="helpId"
                          placeholder="A"
                        />
                      </b-form-group>
                      <b-form-group label="Min score">
                        <b-form-input
                          required
                          type="number"
                          v-model="grade.min"
                          aria-describedby="helpId"
                          placeholder="0"
                        />
                      </b-form-group>
                      <b-form-group label="Max score">
                        <b-form-input
                          required
                          type="number"
                          v-model="grade.max"
                          aria-describedby="helpId"
                          placeholder="100"
                        />
                      </b-form-group>

                      <b-form-group class="my-3">
                        <b-button
                          v-if="!update"
                          type="submit"
                          class="btn btn-primary btn-block"
                        >Create</b-button>
                        <div v-else class="w-100">
                          <b-form-row>
                            <b-col>
                              <b-button type="button" block @click="updateGrade">Update</b-button>
                            </b-col>
                            <b-col>
                              <b-button
                                type="button"
                                block
                                variant="outline-secondary"
                                @click="cancel"
                              >Cancel</b-button>
                            </b-col>
                          </b-form-row>
                        </div>
                      </b-form-group>
                    </form>
                  </div>
                </b-modal>

                <b-modal id="subclass" size="xl" scrollable hide-footer>
                  <div class="add border p-2">
                    <form @submit.prevent="savesubject">
                      <h5 class="text-center">
                        Subject
                        <i class="fa fa-arrows-h mx-2" aria-hidden="true"></i> Class
                      </h5>

                      <div>
                        <label for>Class</label>
                        <b-form-select v-model="choose_class">
                          <b-form-select-option value>Select</b-form-select-option>
                          <b-form-select-option
                            :value="myclass.id"
                            v-for="(myclass,idx) in classes"
                            :key="idx"
                          >{{myclass.class_name}}</b-form-select-option>
                        </b-form-select>
                      </div>

                      <b-form-group label="Choose Subjects">
                        <b-form-row>
                          <b-col cols="4" v-for="(subject,idx) in subjects" :key="idx">
                            <b-form-checkbox
                              :value="subject.name.toLowerCase()"
                              v-model="selected_subjects"
                            >
                              {{subject.name}}(
                              <small>{{subject.code}}</small>)
                            </b-form-checkbox>
                          </b-col>
                        </b-form-row>
                      </b-form-group>

                      <b-form-group class="text-center">
                        <b-button type="submit">Save</b-button>
                      </b-form-group>
                    </form>
                  </div>
                </b-modal>
              </div>

              <!-- <b-table
                :fields="gradeField"
                :items="grades"
                bordered
                class="mt-5 mx-auto w-75"
                v-if="JSON.parse(admin.level).includes('tertiary')"
              >
                <template v-slot:cell(*)="data">
                  <b-icon @click="dropGrade(data.item.id)" icon="trash"></b-icon>
                </template>
              </b-table> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  props: ["admin"],
  data() {
    return {
      swiperOption: {
        slidesPerView:1,
       
        autoplay: {
          delay: 7000,
          disableOnInteraction: false,
        },
      },
      subjects: [],
      departments: [],
      choose_class: "",
      classes: [],
      classesSubject: [],
      selected_subjects: [],
      category:'',
      subject: {
        name: "",
        code: "",
        credit: "",
        session: "first",
        course: "",
        line: "general",
        teacher: [],
        category:'nursery'
      },

      items: [],
      item: false,
      update: false,
      fields: ["subject", "code", "line", "action"],
      tertfields: ["subject", "code",'level' , "action"],
      fieldSubject: ["category", "level", "subjects"],
      grades: [],
      grade: {
        name: "",
        min: null,
        max: null,
      },
      gradeField: ["grade", "min", "max", "*"],
      courses: [],
      searchCourse: "",
      searchTeacher: "",
      tutors: [],
      timeline: [],
      notify: [],
    };
  },
  watch: {
    item: "selectAll",
  },
  mounted() {
    this.getSubjects();
    this.getclasses();
    this.getclassesSubject();
    this.getGrades();
    this.getCourses();
    this.getTutors();
    this.getDepartment();
    this.getTimeline();
    this.getNotify();
  },
  computed: {
    sortSubject(){
       return this.subjects.filter(item=>item.level.toLowerCase().includes(this.category.toLowerCase()));
    },
    sortCourse() {
      return this.departments.filter((item) => {
        return item.name
          .toLowerCase()
          .includes(this.searchCourse.toLowerCase());
      });
    },
    sortTeacher() {
      return this.tutors.filter((item) => {
        return item.name
          .toLowerCase()
          .includes(this.searchTeacher.toLowerCase());
      });
    },
  },
  methods: {
    getTimeline() {
      axios
        .get(`/api/admin-specific-att`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.timeline = res.data;
          }
        });
    },
    getNotify() {
      axios
        .get(`/api/resource-notifications`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.notify = res.data;
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
    getGrades() {
      axios
        .get("/api/grades", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.grades = res.data;
          }
        });
    },
    updateGrade(id) {
      axios
        .put(`/api/grades/${id}`, this.grades, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.getGrades();
          }
        });
    },
    addGrade() {
      axios
        .post("/api/grades", this.grade, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.$toasted.info("Added");
            this.grade = {
              name: "",
              min: null,
              max: null,
            };
            this.$bvModal.hide("grade");
            this.grades.push(res.data);
          }
        });
    },
    cancel() {
      this.update = false;
      this.subject = {
        name: "",
        code: "",
        credit: "",
        session: "first",
        course: "",
        line: "general",
        teacher: [],
      };
    },
    selectAll() {
      if (this.item) {
        this.items = [];
        this.subjects.forEach((it) => {
          this.items.push(it.id);
        });
      } else {
        this.items = [];
      }
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
            this.classes = res.data;
          }
        });
    },
    getclassesSubject() {
      axios
        .get("/api/class-subject", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.classesSubject = res.data;
          }
        });
    },
    savesubject() {
      let data = {
        id: this.choose_class,
        subjects: this.selected_subjects,
      };
      axios
        .post("/api/class-subject", data, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201 || res.status == 200) {
            this.$toasted.success("saved");
            (this.choose_class = ""), (this.selected_subjects = []);
            this.$bvModal.hide("subclass");
          }
        });
    },
    addSubjects() {
      axios
        .post("/api/subject", this.subject, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.$toasted.success("Created");
            this.subject = {
              name: "",
              code: "",
              credit: "",
              session: "first",
              course: "",
              line: "general",
              teacher: [],
              category:'nursery'
            };
            this.getSubjects();
            this.$bvModal.show("classes");
          }
        });
    },
    updateN() {
      axios
        .put(`/api/subject/${this.subject.id}`, this.subject, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.$toasted.success("Updated");
            this.getSubjects();
            this.update = false;
            this.$bvModal.show("classes");
            this.subject = {
              name: "",
              code: "",
              credit: "",
              session: "first",
              course: "",
              line: "general",
              teacher: [],
            };
          }
        });
    },
    edit(id) {
      axios
        .get(`/api/subject/${id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.subject = res.data;
            this.update = true;
            this.$bvModal.show("classes");
          }
        });
    },
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
    dropGrade(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/grades/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getGrades();
            }
          });
      }
    },
    drop(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/subject/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getSubjects();
            }
          });
      }
    },
    multiDrop() {
      let del = confirm("Are you sure about this?");
      if (del) {
        axios
          .post("/api/multi-subjects-drop", this.items, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getsubjects();
            }
          })
          .catch((err) => {
            console.log("del -> err", err);
          });
      }
    },
  },
};
</script>

<style scoped>
nav {
  display: flex;
  justify-content: flex-end;
}
td {
  text-transform: uppercase;
}
.hiden {
  opacity: 0;
}
.search-box {
  padding: 15px;
  border: 1px solid #ccc;
  border-radius: 5px;
  max-height: 130px;
  overflow: hidden;
}
.box {
  max-height: 120px;
  overflow-y: scroll;
}
.add {
  background-color: #f7f8fa;
}
.body {
  padding: 20px 20px 50px;
  height: 100%;
}
.form-group {
  margin-bottom: 24px;
}
.announcement-board {
  background: #fff;
  border-radius: 10px;
  padding: 20px;
}
.announcement-board-content {
  display: flex;
  justify-content: space-between;
}
.board {
  padding-top: 20px;
}
.announcement-title {
  display: flex;
  flex-direction: column;
  justify-content: center;
  width: 75%;
}
.add-subject {
  background: #fff;
  height: 92vh;
  margin-right: -17px;
  position: sticky;
}
.add-subject-btn {
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 30px;
}
.btn-secondary {
  color: #fff;
  background-color: #008e3a;
  border-color: #008e3a;
}
.btn-primary:not(:disabled):not(.disabled):active,
.btn-primary:not(:disabled):not(.disabled).active,
.show > .btn-primary.dropdown-toggle {
  color: #fff;
  background-color: #008e3a;
  border-color: #008e3a;
}
.btn-primary:hover {
  color: #fff;
  background-color: #008e3a;
  border-color: #008e3a;
}
.subject-table {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  margin-top: 10px;
}
@media(max-width:768px){
  img{
    max-width:150px;
  }
  h5{
    font-size:1rem;
  }
  p{
    font-size:15px
  }
}
@media(max-width:425px){
  img{
    max-width:100px;
  }
  h5{
    font-size:1rem;
  }
  p{
    font-size:15px
  }
  .row{
    flex-direction:column-reverse
  }
  .add-subject{
    height:auto;
  }
}
</style>