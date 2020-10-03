<template>
  <div>
    <b-container>
      <div class="timetable-schedule">
        <b-card class="tutor" no-body>
          <b-tabs card justified>
            <b-tab title=" Schedule" active>
              <div class="activity">
                <b-row class="py-5">
                  <b-col md="6">
                    <div class="cards border p-2">
                      <div class="class_section" v-if="todaysClass.length">
                        <div class="class-content p-2" v-for="(item,idx) in todaysClass" :key="idx">
                          <div class="class-content-top">
                            <p class="toCaps">{{item.subject}}</p>
                            <i
                              class="fa fa-play-circle-o green"
                              v-if="(getSecond(today)  > getSecond(item.start)) && (getSecond(today)   < getSecond(item.end))"
                              aria-hidden="true"
                            ></i>

                            <i
                              class="fa fa-dot-circle-o amber"
                              v-else-if="(getSecond(today)  <  getSecond(item.start))"
                              aria-hidden="true"
                            ></i>

                            <i
                              class="fa fa-stop-circle-o red"
                              :id="idx.toString()"
                              v-else-if="(getSecond(today)   >  getSecond(item.start)) && (getSecond(today) >getSecond(item.end))"
                              aria-hidden="true"
                            ></i>
                           
                            
                          </div>
                          <small>{{item.start | format}} to {{item.end | format}}</small>
                          <div class="class-content-main">
                            <p>
                              <span>
                                Tutor :
                                <strong class="toCaps">{{item.tutor}}</strong>
                              </span>
                            </p>

                            <p
                              v-if="(getSecond(today)  > getSecond(item.start)) && (getSecond(today)   < getSecond(item.end))"
                            >Ongoing</p>
                            <p v-else-if="(getSecond(today)  <  getSecond(item.start))"
                            >Upcoming</p>

                            <p
                              v-else-if="(getSecond(today)   >  getSecond(item.start)) && (getSecond(today) >getSecond(item.end))"
                            >Finished</p>
                          
                          </div>
                        </div>
                      </div>
                      <div class="class_section phert-5" v-else>
                        <b-alert show class=" text-center"><b-icon icon="info-circle-fill"></b-icon> No Class Available</b-alert>
                      </div> 
                    </div>
                  </b-col>
                  <b-col md="6">
                    <div v-if="schedules.length">
                      <!-- .filter(item=>new Date().toDateString() == new Date(schedule.date).toDateString())" :key="idx" -->
                      <b-list-group>
                        <b-list-group-item
                          class="d-flex justify-content-between"
                          v-for="(schedule,idx)  in schedules.filter(item=>new Date().toDateString() == new Date(item.date).toDateString())"
                          :key="idx"
                        >
                          {{schedule.description}}
                          <b-icon icon="calendar3"></b-icon>
                        </b-list-group-item>
                      </b-list-group>
                    </div>
                  </b-col>
                </b-row>
              </div>
            </b-tab>
            <b-tab title="Time Table">
              <div class="activity">
                <!-- <div class="d-flex justify-content-around mb-2">
               <span> <b-icon icon="circle-fill" class="upcoming"></b-icon> Upcoming</span>
                <span> <b-icon icon="circle-fill" class="ongoing"></b-icon> Ongoing</span>
                 <span> <b-icon icon="circle-fill" class="finished"></b-icon> Finished</span>
                </div>-->

                <b-table-simple responsive bordered>
                  <b-thead>
                    <b-tr>
                      <b-th>Day</b-th>
                      <b-th>Activity</b-th>
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <b-tr>
                      <b-td class>Monday</b-td>
                      <b-td class="att-td">
                       <b-table-simple class="m-0">
                          <b-tr>
                          <b-td
                            class="p-2 text-center border-right"
                            v-for="(item,index) in allClass.filter(val=>val.day.toLowerCase().includes('monday'))"
                            :key="index"
                          >
                            <div>{{item.start | format}} - {{item.end | format}}</div>
                            <div>{{item.subject}}</div>
                            <div>{{item.level}}</div>
                          </b-td>
                        </b-tr>
                       </b-table-simple>
                      </b-td>
                    </b-tr>
                    <b-tr>
                      
                      <b-td class>Tuesday</b-td>
                      <b-td class="att-td">
                          <b-table-simple class="m-0">
                        <b-tr>
                           
                          <b-td
                            class="p-2 text-center border-right"
                            v-for="(item,index) in allClass.filter(val=>val.day.toLowerCase().includes('tuesday'))"
                            :key="index"
                          >
                            <div>{{item.start | format}} - {{item.end | format}}</div>
                            <div>{{item.subject}}</div>
                            <div>{{item.level}}</div>
                          </b-td>
                            
                        </b-tr>
                         </b-table-simple>
                      </b-td>
                    </b-tr>
                    <b-tr>
                      <b-td class>Wednesday</b-td>
                      <b-td class="att-td">
                           <b-table-simple class="m-0">
                        <b-tr>

                          <b-td
                            class="p-2 text-center border-right"
                            v-for="(item,index) in allClass.filter(val=>val.day.toLowerCase().includes('wednesday'))"
                            :key="index"
                          >
                            <div>{{item.start | format}} - {{item.end | format}}</div>
                            <div>{{item.subject}}</div>
                            <div>{{item.level}}</div>
                          </b-td>
                          
                        </b-tr>
                           </b-table-simple>
                      </b-td>
                    </b-tr>
                    <b-tr>
                      <b-td class>Thursday</b-td>
                      <b-td class="att-td">
                           <b-table-simple class="m-0">
                        <b-tr>
                          <b-td
                            class="p-2 text-center border-right"
                            v-for="(item,index) in allClass.filter(val=>val.day.toLowerCase().includes('thursday'))"
                            :key="index"
                          >
                            <div>{{item.start | format}} - {{item.end | format}}</div>
                            <div>{{item.subject}}</div>
                            <div>{{item.level}}</div>
                          </b-td>
                        </b-tr>
                           </b-table-simple>
                      </b-td>
                    </b-tr>
                    <b-tr>
                      <b-td class>Friday</b-td>
                      <b-td class="att-td">
                            <b-table-simple class="m-0">
                        <b-tr>
                          <b-td
                            class="p-2 text-center border-right"
                            v-for="(item,index) in allClass.filter(val=>val.day.toLowerCase().includes('friday'))"
                            :key="index"
                          >
                            <div>{{item.start | format}} - {{item.end | format}}</div>
                            <div>{{item.subject}}</div>
                            <div>{{item.level}}</div>
                          </b-td>
                        </b-tr>
                           </b-table-simple>
                      </b-td>
                    </b-tr>
                  </b-tbody>
                </b-table-simple>
              </div>
            </b-tab>

            <b-tab title="Calendar">
              <b-container class="activity">
                <b-row>
                  <b-col md="6">
                    <vc-calendar is-expanded :attributes="attributes" :rows="2"></vc-calendar>
                  </b-col>
                  <b-col md="6">
                    <div class="schedule-part">
                      <div class="schedule-inner">
                        <h6>Scheduler</h6>
                        <p>keep reminders with your scheduler</p>
                        <b-form>
                          <b-form-datepicker size="sm" v-model="schedule.date"></b-form-datepicker>
                          <b-input-group class="mt-3">
                            <b-form-input size="sm" v-model="schedule.description"></b-form-input>
                            <b-input-group-append>
                              <b-button size="sm" variant="success" @click="addSchedule">Add Todo</b-button>
                            </b-input-group-append>
                          </b-input-group>
                          <hr />
                        </b-form>
                        <h6>Schedule</h6>
                        <b-col >
                          <div class="cards">
                            <div class="class_section" v-if="schedules.length">
                              <b-list-group>
                                <b-list-group-item
                                  class="d-flex justify-content-between align-items-center"
                                  v-for="(schedule,idx)  in schedules"
                                  :key="idx"
                                >
                                  <span>
                                    <b-icon icon="calendar3" class="mr-2"></b-icon>
                                    {{schedule.description}}
                                  </span>
                                  <b-icon icon="x" @click="drop(schedule.id)"></b-icon>
                                </b-list-group-item>
                              </b-list-group>
                            </div>
                            <div
                              v-else
                              class="class_section d-flex justify-content-center align-items-center"
                            >
                              <p class="pt-5">Nothing Scheduled</p>
                            </div>
                          </div>
                        </b-col>
                      </div>
                    </div>
                  </b-col>
                </b-row>
              </b-container>
            </b-tab>
            <b-tab title="Timeline">

              <b-row class="text-left mb-3">
                  <b-col md="3">
                      <div class="text-right">
                        <b-form-select size="sm" v-model="week" class="week mb-3">
                          <b-form-select-option
                            :value="num"
                            v-for="(num,id) in n"
                            :key="id"
                          >{{getWeek(new Date())==num?'Current week ':'Week' }} {{num}}</b-form-select-option>
                        </b-form-select>
                      </div>
                    </b-col>
               <!-- <b-col cols="3">
                  <b-form-datepicker class="selectday" v-model="day"></b-form-datepicker>
               </b-col> -->
                 <b-col md="3">
                      <div class="text-right">
                        <b-form-select size="sm" v-model="day" class="week mb-3">
                          <b-form-select-option
                            :value="num.value"
                            v-for="(num,id) in days"
                            :key="id"
                          >{{num.key}}</b-form-select-option>
                        </b-form-select>
                      </div>
                    </b-col>
              </b-row>

              <div class="mytimeline">
                <div class="timeline">
                  <div
                    class="container-time"
                    v-for="(time,idx) in sortTimeline"
                    :key="idx"
                    :class="{right:idx%2,left:!idx%2}"
                  >
                    <div class="content">
                      <h5>{{time.date}}</h5>
                      <p>{{handleTimeline(time.record,time.created_at)}}</p>
                    </div>
                  </div>
                </div>
              </div>
            </b-tab>
          </b-tabs>
        </b-card>
      </div>
    </b-container>
  </div>
</template>

<script>
import Calendar from "v-calendar/lib/components/calendar.umd";
export default {
  components: {
    Calendar,
  },
  props: ["tutor"],
  data() {
    return {
      n: 10,
      attendance: [],
      todaysClass: [],
      level: "",
      myclass: [],
      allstudnts: [],
      subject: "",
      participation: [],

      schedules: [],
      schedule: {
        date: "",
        description: "",
      },
      tables: [],
      n: 52,
      week: "",
      table: [],
      myclass: [],
      overlay: false,
      item: false,
      my_class: "",
      items: [],
      filterShow: false,
      todaysClass: [],
      today:
        new Date().getHours() +
        ":" +
        new Date().getMinutes() +
        ":" +
        new Date().getSeconds(),
      allClass: [],
      attendance: [],
      my_class: "",
      all_class: [],
      timeline: [],
      day: new Date().getDay(),
      days: [
       
        { key: "Monday", value: 1 },
        { key: "Tuesday", value: 2 },
        { key: "Wednesday", value: 3 },
        { key: "Thursday", value: 4 },
        { key: "Friday", value: 5 },
        { key: "Saturday", value: 6 },
        { key: "Sunday", value: 7 },
      ],
    };
  },
  mounted() {
    this.getTodayClass();
    this.getStud();
    this.getParticipation();
    this.getTimeline();
    this.getTable();
    this.getTodayClass();
    this.getallClass();
    this.getAttendance();
    this.getClasses();
    this.getSchedule();
    this.week = this.getWeek(new Date());
  },
  computed: {
    sortTimeline() {
     var week = this.timeline.filter((item) => {
        return this.getWeek(item.date) == this.week;
      });

      return week.filter(item=> new Date(item.date).getDay() == this.day)
    },
    sortedStuds() {
      return this.allstudnts.filter((item) =>
        item.sub_class.toLowerCase().includes(this.level.toLowerCase())
      );
    },
    sortedAtt() {
      return this.attendance.filter((item) =>
        item.subject.toLowerCase().includes(this.subject.toLowerCase())
      );
    },
    sortedPart() {
      return this.participation.filter((item) =>
        item.subject.toLowerCase().includes(this.subject.toLowerCase())
      );
    },

    sorted() {
      var first = this.attendance.filter(
        (item) => this.getWeek(item.date) == this.week
      );
      return first.filter((item) =>
        item.level.toLowerCase().includes(this.my_class.toLowerCase())
      );
    },
    attributes() {
      return [
        ...this.schedules.map((todo) => ({
          dates: new Date(todo.date),
          highlight: true,
          popover: {
            label: todo.description,
          },
          customData: todo,
        })),
      ];
    },
    sortedTable() {
      return this.tables.filter((item) =>
        item.myclass.toLowerCase().includes(this.my_class.toLowerCase())
      );
    },
  },
  methods: {
    sortTime(time) {
      // Check correct time format and split into components
      time = time
        .toString()
        .match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

      if (time.length > 1) {
        // If time format correct

        time = time.slice(1); // Remove full string match value
        time[3] = " ";
        time[5] = +time[0] < 12 ? "AM" : "PM"; // Set AM/PM
        time[0] = +time[0] % 12 || 12; // Adjust hours
      }

      return time.join(""); // return adjusted time or original string
    },
    handleTimeline(type, time) {
      if (type == "logged") {
        return (
          "You logged in at " +
          this.sortTime(new Date(time).toLocaleTimeString())
        );
      }
      if (type == "logged out") {
        return (
          "You logged out at " +
          this.sortTime(new Date(time).toLocaleTimeString())
        );
      }
      if (type == "new assessment") {
        return (
          "You created a new assessment at " +
          this.sortTime(new Date(time).toLocaleTimeString())
        );
      }
      if (type == "new resource") {
        return (
          "You uploaded a new resource at" +
          this.sortTime(new Date(time).toLocaleTimeString())
        );
      }
      if (type == "Enter class") {
        return (
          "You started a class at " +
          this.sortTime(new Date(time).toLocaleTimeString())
        );
      }
    },
    getTimeline() {
      axios
        .get(`/api/tutor-specific-att`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.timeline = res.data;
          }
        });
    },
    addSchedule() {
      axios
        .post(`/api/tutor-schedule`, this.schedule, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.schedule.date = "";
            this.schedule.description = "";
            this.schedules.push(res.data);
          }
        });
    },
    drop(id) {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/tutor-schedule/${id}`, {
            headers: {
              Authorization: `Bearer ${tutor.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getSchedule();
            }
          });
      }
    },
    getSchedule() {
      axios
        .get(`/api/tutor-schedule`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.schedules = res.data;
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
    getAttendance() {
      axios
        .get(`/api/tutor-get-attendance`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.attendance = res.data;
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
        .get(`/api/current-class`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.todaysClass = res.data;
          }
        });
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
            res.data.forEach((item) => {
              // this.allClass.push(item.class_name);
              if (item.sub_class !== "") {
                item.sub_class.split(",").forEach((i) => {
                  this.all_class.push(i);
                });
              }
            });
            this.my_class = this.all_class[0];
          }
        });
    },
    getallClass() {
      axios
        .get(`/api/all-class`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.allClass = res.data;
          }
        });
    },
    cancel() {
      this.overlay = false;
      this.tables = [];
      this.myclass = "";
    },

    toggleFilter() {
      this.filterShow = !this.filterShow;
    },
    getTable() {
      axios
        .get(`/api/tutor-times-table`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.tables = res.data;
            this.myclass = res.data.filter((item) => item.myclass);
            this.my_class = this.myclass[0].myclass;
          }
        });
    },

    getParticipation() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      axios
        .get(`/api/participation`, {
          headers: { Authorization: `Bearer ${tutor.access_token}` },
        })
        .then((res) => {
          if (res.status == 200) {
            this.participation = res.data;
          }
        });
    },
    handleParticipation(e, item) {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      let data = {
        score: e,
        subject: this.subject,
        tutor: tutor.id,
        user_id: item.id,
        day: new Date()
          .toLocaleString("default", { weekday: "long" })
          .toLowerCase(),
        date: new Date().toLocaleDateString(),
      };
      axios
        .post(`/api/participation`, data, {
          headers: { Authorization: `Bearer ${tutor.access_token}` },
        })
        .then((res) => {
          if (res.status == 201) {
            this.getParticipation();
          }
          if (res.status == 200) {
            this.getParticipation();
          }
        });
    },
    mark(user, val, value) {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));

      let data = {
        user_id: user.id,
        value: value,
        subject: val.subject,
      };
      axios
        .put(`/api/update-attendance/${val.id}`, data, {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.getAtt();
          }
        });
    },

    getStud() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      axios
        .get(`/api/tutor-all-students`, {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.allstudnts = res.data;
          }
        });
    },
    getTodayClass() {
      axios
        .get(`/api/current-class`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.todaysClass = res.data;
          }
        });
    },
  },
};
</script>


<style scoped>
.container {
  padding-top: 30px;
  padding-bottom: 70px;
}
.body {
  position: relative;
  padding: 40px 20px 50px;
  height: 100%;
}
.activity {
  max-height: 80vh;
  overflow: auto;
}
.overlay-content {
  position: absolute;
  top: 0px;
  left: 0px;
  bottom: 0px;
  right: 0px;
  opacity: 0.85;
  backdrop-filter: blur(2px);
  height: 100vh;
  width: 100%;
  left: 0;
  background: rgba(0, 0, 0, 0.8);
  padding: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
}
.cancel {
  position: absolute;
  right: 10px;
  top: 10px;
}
.ov {
  width: 100%;
  position: relative;
}

.sort {
  width: 120px;
}
.schedule-part {
  background: #fff;
  height: 100vh;
}
.schedule-inner {
  padding: 20px;
  height: 100vh;
  overflow-y: scroll;
}
.schedule-inner p {
  font-size: 14px;
}
.schedule-inner h6 {
  padding-top: 20px;
}
.border-right {
  border: 0 !important;
  border-right: 1px solid #dee2e6 !important;
}
.notify {
  display: flex;
  justify-content: space-between;
  padding-bottom: 15px;
}
.notify-i i {
  color: #22cade;
  padding: 10px;
}
.notify-i {
  background: rgba(34, 202, 222, 0.25);
  border-radius: 50%;
}
.noftify-word p {
  font-size: 12px;
}
/* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  background: none;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: rgba(34, 202, 222, 0.25);
  border-radius: 5px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555;
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
.cal {
  text-align: left;
  padding: 10px;
  width: 100px;
}
h5{
  font-size:16px;
}
.main-content {
  display: flex;
  border-bottom: 1px solid #ccc;
  padding: 15px;
}
.main-content p {
  margin-bottom: 2px;
  padding-left: 15px;
}
.main-content i {
  color: #22cade;
  padding-top: 3px;
  font-size: 24px;
}
ul {
  list-style: none;
  margin: 0;
  font-size: 12px;
  color: #333;
  max-height: 60px;
  overflow: auto;
  padding: 5px 0;
  border-bottom: 1px solid #ccc;
}
ul li {
  padding: 5px 0;
}
.nav-link {
  color: #000 !important;
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
}
.class-content-top {
  display: flex;
  justify-content: space-between;
}
.cards {
  background: #fff;
  border-radius: 10px;
  padding: 10px;
}
.class-content {
  padding-bottom: 10px;
}
.class_section {
  max-height: 340px;
  overflow: auto;
}
.class-content-top {
  display: flex;
  justify-content: space-between;
}

.class-content-main {
  display: flex;
  justify-content: space-between;
}
.class-content-main p {
  font-size: 12px;
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
  font-size: 16px;
}
.notes-top {
  display: flex;
  justify-content: space-between;
}
.btn {
  background: transparent;
  border: 1px solid #13a699;
  border-radius: 5px;
  color: #13a699;
  font-size: 15px;
}

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
.sort-table p {
  font-size: 14px;
}
.main-attendance {
  padding-top: 20px;
  font-family: "Montserrat";
}
.attendance {
  background: #fff;
  padding: 20px;
}
.absent {
  color: #ff0000;
}
.present {
  color: green;
}
.select {
  width: 300px;
}
.part {
  width: 180px;
}

/* The actual timeline (the vertical ruler) */
.timeline {
  background-color: #474e5d;
  font-family: Helvetica, sans-serif;
  position: relative;
  max-width: 1200px;
  margin: 0 auto;

  overflow: auto;
}
.att-td {
  padding: 0;
}
/* The actual timeline (the vertical ruler) */
.mytimeline{
  max-height: 70vh;
  overflow:auto;
}
.timeline::after {
  content: "";
  position: absolute;
  width: 6px;
  background-color: white;
  top: 0;
  bottom: 0;
  left: 50%;
  margin-left: -3px;
}

/* Container around content */
.container-time {
  padding: 10px 40px;
  position: relative;
  background-color: inherit;
  width: 50%;
}

/* The circles on the timeline */
.container-time::after {
  content: "";
  position: absolute;
  width: 25px;
  height: 25px;
  right: -17px;
  background-color: white;
  border: 4px solid #ff9f55;
  top: 15px;
  border-radius: 50%;
  z-index: 1;
}

/* Place the container to the left */
.left {
  left: 0;
}

/* Place the container to the right */
.right {
  left: 50%;
}

/* Add arrows to the left container (pointing right) */
.left::before {
  content: " ";
  height: 0;
  position: absolute;
  top: 22px;
  width: 0;
  z-index: 1;
  right: 30px;
  border: medium solid white;
  border-width: 10px 0 10px 10px;
  border-color: transparent transparent transparent white;
}

/* Add arrows to the right container (pointing left) */
.right::before {
  content: " ";
  height: 0;
  position: absolute;
  top: 22px;
  width: 0;
  z-index: 1;
  left: 30px;
  border: medium solid white;
  border-width: 10px 10px 10px 0;
  border-color: transparent white transparent transparent;
}

/* Fix the circle for containers on the right side */
.right::after {
  left: -16px;
}

/* The actual content */
.content {
  padding: 20px 30px;
  background-color: white;
  position: relative;
  border-radius: 6px;
}

/* Media queries - Responsive timeline on screens less than 600px wide */
@media screen and (max-width: 600px) {
  /* Place the timelime to the left */
  .timeline::after {
    left: 31px;
  }
.content{
  padding:20px 15px;
}
  /* Full-width containers */
  .container-time {
    width: 100%;
    padding-left: 70px;
    padding-right: 25px;
  }

  /* Make sure that all arrows are pointing leftwards */
  .container-time::before {
    left: 60px;
    border: medium solid white;
    border-width: 10px 10px 10px 0;
    border-color: transparent white transparent transparent;
  }

  /* Make sure all circles are at the same spot */
  .left::after,
  .right::after {
    left: 15px;
  }

  /* Make all right containers behave like the left ones */
  .right {
    left: 0%;
  }
  .schedule-inner{
    padding:0
  }
  td{
    min-width:150px;
  }
}
</style>