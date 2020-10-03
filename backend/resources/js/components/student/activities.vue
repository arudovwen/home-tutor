<template>
  <div>
    <b-modal id="registercourse" scrollable hide-footer>
      <div class="add border p-2">
        <form @submit.prevent="register">
          <h5 class="text-center">Course Registration</h5>

          <b-form-group class="mb-4">
            <b-form-input
              size="sm"
              placeholder="Search"
              v-model="searchCourse"
            ></b-form-input>
            <b-input-group class="mt-3">
              <b-form-select v-model="subject">
                <b-form-select-option value>Choose Course</b-form-select-option>
                <b-form-select-option
                  :value="item"
                  v-for="(item, idx) in sortCourse"
                  :key="idx"
                  >{{ item.name }}</b-form-select-option
                >
              </b-form-select>
              <b-input-group-append>
                <b-button @click="addCourse" variant="info">Add</b-button>
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
          <b-form-group>
            <b-table-simple v-if="mycourses.length">
              <b-thead>
                <b-tr>
                  <b-th>Course</b-th>
                  <b-th>Credit</b-th>
                  <b-th></b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <b-tr v-for="(item, ids) in mycourses" :key="ids">
                  <b-td>{{ item.name }}</b-td>
                  <b-td>{{ item.credit }}</b-td>
                  <b-td>
                    <b-icon icon="x" @click="removeCourse(ids)"></b-icon>
                  </b-td>
                </b-tr>
                <b-tr class="total">
                  <b-td>Total Credit</b-td>
                  <b-td>{{ totalCredit }}</b-td>
                  <b-td></b-td>
                </b-tr>
              </b-tbody>
            </b-table-simple>
          </b-form-group>
          <b-form-group>
            <b-button size="sm" type="submit">Submit</b-button>
          </b-form-group>
        </form>
      </div>
    </b-modal>

    <b-container>
      <div class="container">
        <b-card no-body class="activities-card assess">
          <b-tabs card justified>
            <b-tab title="My Schedule" active>
              <div class="activity">
                <b-row class="py-5">
                  <b-col md="5">
                    <div class="cards border p-2">
                      <div class="class_section" v-if="todaysClass.length">
                        <div
                          class="class-content p-2 border-bottom"
                          v-for="(item, idx) in todaysClass"
                          :key="idx"
                        >
                          <div class="class-content-top">
                            <p class="toCaps">{{ item.subject }}</p>
                            <i
                              class="fa fa-play-circle-o green"
                              v-if="
                                getSecond(today) > getSecond(item.start) &&
                                getSecond(today) < getSecond(item.end)
                              "
                              aria-hidden="true"
                            ></i>

                            <i
                              class="fa fa-dot-circle-o amber"
                              v-else-if="
                                getSecond(today) < getSecond(item.start)
                              "
                              aria-hidden="true"
                            ></i>

                            <i
                              class="fa fa-stop-circle-o red"
                              :id="idx.toString()"
                              v-else-if="
                                getSecond(today) > getSecond(item.start) &&
                                getSecond(today) > getSecond(item.end)
                              "
                              aria-hidden="true"
                            ></i>
                          </div>
                          <small
                            >{{ item.start | format }} to
                            {{ item.end | format }}</small
                          >
                          <div class="class-content-main">
                            <p>
                              <span>
                                Tutor :
                                <strong class="toCaps">{{ item.tutor }}</strong>
                              </span>
                            </p>

                            <p
                              v-if="
                                getSecond(today) > getSecond(item.start) &&
                                getSecond(today) < getSecond(item.end)"
                            >
                              Ongoing
                            </p>
                            <!-- <p v-else-if="(getSecond(today)  <  getSecond(item.start))"
                            >Upcoming</p> -->

                            <p
                              v-else-if="
                                getSecond(today) > getSecond(item.start) &&
                                getSecond(today) > getSecond(item.end)"
                            >
                              Finished
                            </p>
                          </div>
                        </div>
                      </div>
                      <div class="class_section p-5" v-else>
                        <b-alert show>No Class Available</b-alert>
                      </div>
                    </div>
                  </b-col>
                  <b-col md="7">
                    <div
                      class="class-content p-2 mb-3 rounded d-flex border"
                      v-for="(item, idx) in assess.filter(
                        (item) =>
                          new Date(item.end).getTime() > new Date().getTime()
                      )"
                      :key="idx"
                    >
                      <b-icon
                        icon="clock"
                        variant="success"
                        class="mr-2"
                      ></b-icon>
                      <div class>
                        {{ item.subject }} {{ item.type }} submission deadline
                        {{ new Date(item.end).toDateString() }}
                      </div>
                    </div>
                  </b-col>
                </b-row>
              </div>
            </b-tab>
            <b-tab title="My TimeTable" class="time-table-tab">
              <div class="time-table">
                <h5 class="mb-4">{{ student.sub_class.toUpperCase() }}</h5>

                <div class="d-flex justify-content-around mb-2">
                  <span>
                    <b-icon icon="circle-fill" class="upcoming mr-1"></b-icon
                    >Upcoming
                  </span>
                  <span>
                    <b-icon icon="circle-fill" class="ongoing mr-1"></b-icon
                    >Ongoing
                  </span>
                  <span>
                    <b-icon icon="circle-fill" class="finished mr-1"></b-icon
                    >Finished
                  </span>
                </div>
                <div class="times">
                  <div class="activity-table">
                    <table class="table table-bordered">
                      <thead class="thead-light">
                        <tr>
                          <th>Day</th>
                          <th>Time</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(tab, index) in table" :key="index">
                          <td class="toCaps day">{{ tab.day }}</td>
                          <td class="d-flex justify-content-between p-0">
                            <table class="w-100">
                              <tr>
                                <td
                                  class="text-center"
                                  :class="{
                                    'upcoming-bg':
                                      getSecond(today) <
                                        getSecond(item.start) &&
                                      tab.day.toLowerCase() == today_name,
                                    'ongoing-bg':
                                      getSecond(today) >
                                        getSecond(item.start) &&
                                      getSecond(today) <= getSecond(item.end) &&
                                      tab.day.toLowerCase() == today_name,
                                    'finished-bg':
                                      getSecond(today) > getSecond(item.end) &&
                                      tab.day.toLowerCase() == today_name,
                                  }"
                                 
                                  v-for="(item, idx) in handleArray(tab.courses)"
                                  :key="idx"
                                >
                                  <div class >
                                    
                                    <div class="toLower">
                                      {{ item.start | format }} -
                                      {{ item.end | format }}
                                    </div>
                                    <div class="toCaps">{{ item.subject }}</div>
                                    <div class="toCaps">{{ item.tutor }}</div>
                                  </div>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </b-tab>
            <b-tab title="Class TimeTable" class="time-table-tab">
              <div class="time-table">
                <h5 class="mb-4">{{ student.sub_class.toUpperCase() }}</h5>

                <div class="d-flex justify-content-around mb-2">
                  <span>
                    <b-icon icon="circle-fill" class="upcoming mr-1"></b-icon
                    >Upcoming
                  </span>
                  <span>
                    <b-icon icon="circle-fill" class="ongoing mr-1"></b-icon
                    >Ongoing
                  </span>
                  <span>
                    <b-icon icon="circle-fill" class="finished mr-1"></b-icon
                    >Finished
                  </span>
                </div>
                <div class="times">
                  <div class="activity-table">
                    <table class="table table-bordered">
                      <thead class="thead-light">
                        <tr>
                          <th>Day</th>
                          <th>Time</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(tab, index) in table" :key="index">
                          <td class="toCaps day">{{ tab.day }}</td>
                          <td class="d-flex justify-content-between p-0">
                            <table class="w-100">
                              <tr>
                                <td
                                  class="text-center"
                                  :class="{
                                    'upcoming-bg':
                                      getSecond(today) <
                                        getSecond(item.start) &&
                                      tab.day.toLowerCase() == today_name,
                                    'ongoing-bg':
                                      getSecond(today) >
                                        getSecond(item.start) &&
                                      getSecond(today) <= getSecond(item.end) &&
                                      tab.day.toLowerCase() == today_name,
                                    'finished-bg':
                                      getSecond(today) > getSecond(item.end) &&
                                      tab.day.toLowerCase() == today_name,
                                  }"
                                  v-for="(item, idx) in tab.courses"
                                  :key="idx"
                                >
                                  <div class>
                                    <div class="toLower">
                                      {{ item.start | format }} -
                                      {{ item.end | format }}
                                    </div>
                                    <div class="toCaps">{{ item.subject }}</div>
                                    <div class="toCaps">{{ item.tutor }}</div>
                                  </div>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </b-tab>
            <b-tab title="My Attendance">
              <div class="activity">
                <div class="main-attendance">
                  <div class="attendance">
                    <div class="sort-table">
                      <div>
                        <b-row>
                          <b-col md="2">
                            <p>
                              <strong>Attendance</strong>
                            </p>
                          </b-col>
                        </b-row>
                      </div>
                    </div>
                    <hr />

                    <div class="attendance-table">
                      <b-row>
                        <b-col cols="12" sm="3">
                          <div>
                            <b-form-select
                              v-model="week"
                              class="week mb-3"
                              size="sm"
                            >
                              <b-form-select-option
                                :value="num"
                                v-for="(num, id) in n"
                                :key="id"
                                >{{
                                  getWeek(new Date()) == num
                                    ? "Current week "
                                    : "Week"
                                }}
                                {{ num }}</b-form-select-option
                              >
                            </b-form-select>
                          </div>
                        </b-col>
                        <b-col cols="6" sm="2">
                          <div>
                            <b-form-select
                              v-model="day"
                              class="week mb-3"
                              size="sm"
                            >
                              <b-form-select-option
                                :value="num.value"
                                v-for="(num, id) in days"
                                :key="id"
                                >{{ num.key }}</b-form-select-option
                              >
                            </b-form-select>
                          </div>
                        </b-col>
                      </b-row>

                      <b-table
                        :items="sortedAtt"
                        :fields="att"
                        bordered
                        v-if="sortedAtt.length"
                        responsive
                        class="attendnce-table"
                      >
                        <template v-slot:cell(attendance)="data">
                          <div class="atte">
                            <span>
                              <b-button
                                size="sm"
                                v-if="data.item.record == '1'"
                                variant="success"
                              >
                                <b-icon
                                  icon="check-circle-fill"
                                  style="width: 14px"
                                ></b-icon>
                                Present
                              </b-button>
                              <b-button
                                size="sm"
                                variant="danger"
                                v-if="data.item.record == '0'"
                              >
                                <b-icon
                                  icon="x-circle-fill"
                                  style="width: 14px"
                                ></b-icon>
                                Absent
                              </b-button>
                              <b-button
                                size="sm"
                                variant="info"
                                v-if="data.item.record == 'pending'"
                              >
                                <b-icon
                                  icon="clock"
                                  style="width: 14px"
                                ></b-icon>
                                Pending
                              </b-button>
                            </span>
                          </div>
                        </template>
                        <template v-slot:cell(participation)="data">
                          <div class="atte">
                            {{ data.item.participation_score }}
                          </div>
                        </template>
                        <template v-slot:cell(subject)="data">
                          <div class="toCaps">{{ data.item.subject }}</div>
                        </template>
                      </b-table>
                      <b-alert show v-else>No record available</b-alert>
                    </div>
                  </div>
                </div>
              </div>
            </b-tab>
            <b-tab
              title="Course Registration"
              v-if="student.course_level == 'tertiary'"
            >
              <b-row >
                <b-col  cols="5">
                  <b-form-group label="Current Level">
                    <b-form-select v-model="level" size="sm">
                      <b-form-select-option
                        :value="item"
                        v-for="(item, idx) in levels"
                        :key="idx"
                        >{{ item }}</b-form-select-option
                      >
                    </b-form-select>
                  </b-form-group>
                </b-col>
              </b-row>
              <div>
                <div class="my-2">Total Credit : {{ gettotal() }}</div>
                <div class="text-right">
                  <b-button size="sm" class="mr-3" v-b-modal.registercourse>
                    Register Course</b-button
                  >
                  <!-- <b-button size="sm">Update Registration</b-button> -->
                </div>
                <b-table-simple v-if="student_courses.length" responsive>
                  <b-thead>
                    <b-tr>
                      <b-th>Courses</b-th>
                      <b-th>Credit</b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody v-if="student_courses.length">
                    <b-tr
                      v-for="(item, idx) in JSON.parse(sortVal.courses)"
                      :key="idx"
                    >
                      <b-td class="toUpper">{{ item.name }}</b-td>
                      <b-td>{{ item.credit }}</b-td>
                    </b-tr>
                  </b-tbody>
                </b-table-simple>
              </div>
            </b-tab>
          </b-tabs>
        </b-card>
      </div>
    </b-container>
  </div>
</template>

<script>
export default {
  props: ["student"],
  data() {
    return {
      n: 52,
      week: "",
      table: [],
      assess: [],
      todaysClass: [],
      mysubject: [],
      courseField: ["Courses"],
      today_name: new Date()
        .toLocaleString("default", { weekday: "long" })
        .toLowerCase(),
      today:
        new Date().getHours() +
        ":" +
        new Date().getMinutes() +
        ":" +
        new Date().getSeconds(),
      attendance: [],
      att: ["day", "subject", "attendance", "participation"],
      day: new Date()
        .toLocaleString("default", { weekday: "long" })
        .toLowerCase(),
      days: [
        { key: "All", value: "" },
        { key: "Monday", value: "monday" },
        { key: "Tuesday", value: "tuesday" },
        { key: "Wednesday", value: "wednesday" },
        { key: "Thursday", value: "thursday" },
        { key: "Friday", value: "friday" },
        { key: "Saturday", value: "saturday" },
        { key: "Sunday", value: "sunday" },
      ],
      subjects: [],
      subject: "",
      mycourses: [],
      student_courses: [],
      totalCredit: [],
      searchCourse: "",
      levels: [],
      level: "",
    };
  },
  mounted() {
    this.getTable();
    this.getTodayClass();
    this.getData();
    this.getAttendance();
    this.week = this.getWeek(new Date());
    this.getRegisteredCourses();
    this.getSubjects();
    this.getSub();
  },
  computed: {
    mytable() {
      var mine = this.mysubject.map((it) => {
        return it.name.toLowerCase();
      });
     
       
      return mine;
    },
    sortCourse() {
      return this.subjects.filter((item) => {
        return item.name
          .toLowerCase()
          .includes(this.searchCourse.toLowerCase());
      });
    },
    sortVal() {
      return this.student_courses.find((item) => {
        return item.level == this.level;
      });
    },
    sorted() {
      return this.attendance.filter(
        (item) => this.getWeek(item.day) == this.week
      );
    },
    sortedAtt() {
      var mweek = this.attendance.filter(
        (item) => this.getWeek(item.day) == this.week
      );

      return mweek.filter((item) =>
        new Date(item.day)
          .toLocaleString("default", { weekday: "long" })
          .toLowerCase()
          .includes(this.day.toLowerCase())
      );
    },
  },

  methods: {
    handleArray(arr){
  var val =  arr.filter(item=>this.mytable.includes(item.subject.toLowerCase()))
  return val
    },
    getSub() {
      axios
        .get(`/api/getsub`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if ((res.status = 200)) {
            this.mysubject = res.data;
          }
        });
    },
    gettotal() {
      if (this.student_courses.length) {
        return (
          JSON.parse(this.student_courses[0].courses)
            .map((item) => {
              return item.credit;
            })
            .reduce((a, b) => {
              return a + b;
            }, 0) || 0
        );
      }
    },
    addCourse() {
      if (
        !this.mycourses
          .map((item) => {
            return item.name;
          })
          .includes(this.subject.name)
      ) {
        let data = {
          name: this.subject.name,
          credit: this.subject.credit,
        };
        this.mycourses.push(data);
        this.totalCredit = this.mycourses
          .map((item) => {
            return item.credit;
          })
          .reduce((a, b) => {
            return a + b;
          }, 0);
      } else {
        this.$toasted.info("Already registered");
      }
    },
    removeCourse(id) {
      this.mycourses.splice(id, 1);
    },
    getSubjects() {
      axios
        .get("/api/student-all-subjects", {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if ((res.status = 200)) {
            this.subjects = res.data;
          }
        });
    },
    getRegisteredCourses() {
      let student = JSON.parse(localStorage.getItem("typeStudent"));
      axios
        .get(`/api/student-courses`, {
          headers: {
            Authorization: `Bearer ${student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.student_courses = res.data;
            if (res.data.length) {
              res.data.forEach((item) => {
                this.levels.push(item.level);
              });
              this.level = this.levels[0];
            }
          }
        });
    },
    register() {
      let student = JSON.parse(localStorage.getItem("typeStudent"));
      let data = {
        choosen_course: this.mycourses,
      };
      axios
        .post(`/api/student-courses`, data, {
          headers: {
            Authorization: `Bearer ${student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.getRegisteredCourses();
          }
        });
    },
    getData() {
      let student = JSON.parse(localStorage.getItem("typeStudent"));
      axios
        .get(`/api/student-assessments/${student.level}`, {
          headers: {
            Authorization: `Bearer ${student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.assess = res.data;
          }
        })
        .catch((err) => {
          err.response.data;
          console.log(" err.response.data", err.response.data);
        });
    },
    getTable() {
      axios
        .get(`/api/student-times-table/${this.$props.student.student_level}`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.table = JSON.parse(res.data[0].table);
          }
        });
    },
    getSecond(hms) {
      var a = hms.split(":"); // split it at the colons

      // minutes are worth 60 seconds. Hours are worth 60 minutes.
      var seconds = +a[0] * 60 * 60 + +a[1] * 60 + +a[2];
      return seconds;
    },
    getTodayClass() {
      axios
        .get(`/api/todays-class/${this.$props.student.sub_class}`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            if (res.data.length) {
              this.todaysClass = res.data[0].courses;
            }
          }
        });
    },
    getAttendance() {
      axios
        .get(`/api/student-sorted-attendance`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.attendance = res.data;
          }
        });
    },
    getWeek(date) {
      var date = new Date(date);
      date.setHours(0, 0, 0, 0);
      // Thursday in current week decides the year.
      date.setDate(date.getDate() + 3 - ((date.getDay() + 6) % 7));
      // January 4 is always in week 1.
      var week1 = new Date(date.getFullYear(), 0, 4);
      // Adjust to Thursday in week 1 and count number of weeks from date to week1.
      return (
        1 +
        Math.round(
          ((date.getTime() - week1.getTime()) / 86400000 -
            3 +
            ((week1.getDay() + 6) % 7)) /
            7
        )
      );
    },
  },
};
</script>


<style scoped>
.activity {
  height: 70vh;
  overflow-y: scroll;
  padding: 20px;
  font-size: 15px;
}
.atte {
  width: 100px;
}
.toLower {
  text-transform: lowercase;
}
.m-res {
  height: 520px;
  overflow: auto;
}
.main-note {
  height: 290px;
  overflow: auto;
}

small,
.small {
  font-size: 100%;
}
.resources-inner {
  border-bottom: 1px solid #e4e4e4;
  padding-top: 5px;
}
.resources-inner:last-child {
  border-bottom: none;
}
.class-content {
  padding-bottom: 10px;
  font-size: 15px;
  align-items: baseline;
}
.class-content-top {
  display: flex;
  justify-content: space-between;
}
.time-table {
  padding: 15px;
}

.red {
  color: #ff0000;
}
.green {
  color: green;
}
.class-content-main {
  display: flex;
  justify-content: space-between;
}
.class-content-main p {
  font-size: 14px;
}
.notes-top {
  display: flex;
  justify-content: space-between;
}
.activities-card {
  margin-top: 20px;
}
/* .btn {
  background: transparent;
  border: 1px solid #13a699;
  border-radius: 5px;
  color: #13a699;
  font-size: 15px;
} */

.my-icon {
  color: #008e3a;
}
.check_it {
  color: #008e3a;
  font-size: 12px;
}
.ongoing {
  color: #008e3a;
}
.upcoming {
  color: #ffc200;
}
.finished {
  color: red;
}
.week {
  width: 180px;
  margin-left: auto;
}
.ongoing-bg {
  /* background-color: #008e3a; */
  background-color: hsl(145deg 100% 85%);
}
.upcoming-bg {
  /* background-color: #ffc200; */
  background-color: hsl(46deg 100% 85%);
}
.finished-bg {
  /* background-color: red; */
  background-color: hsl(0deg 100% 85%);
}
p {
  font-size: 15px;
}
.total {
  border: 2px solid;
}
@media screen and (max-width: 600px) {
  .activity {
    font-size: 14px;
  }
  .time-table-tab {
    margin-bottom: 10px;
    overflow-y: scroll;
    height: 70vh;
  }
  .activity-table {
    overflow: scroll;
  }
  .main-attendance {
    padding: 15px;
  }
  .card-body {
    padding: 15px !important;
  }
  .class-content {
    font-size: 13.5px;
  }
  .assess .nav-tabs .nav-link {
    color: #fff !important;
    font-size: 10px !important;
  }
  table {
    font-size: 13px;
  }
  td {
    min-width: 150px;
  }
  .activity {
    padding: 0;
  }
  .d-flex span{
    font-size:13px;
  }
}
</style>