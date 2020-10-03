<template>
  <div>
    <b-container>
      <b-row>
        <b-col>
          <b-card no-body class="assess">
            <b-tabs card :vertical="!mobile" :end="!mobile">
              <b-tab title="Overall" active>
                <div class="grade_book overall-gradebook-student">
                  <h5 class="mb-5">Overall Grades</h5>
                  <!-- Default checked -->
                  <div class="d-flex align-items-center mb-3 justify-content-end">
                    <label for="customSwitch1" class="m-0"></label>
                    <div class="custom-control custom-switch">
                      <input
                        type="checkbox"
                        class="custom-control-input"
                        id="customSwitch1"
                        v-model="switchT"
                      />
                      <label class="custom-control-label" for="customSwitch1">Percentage</label>
                    </div>
                  </div>
                  <div class="times">
                    <div class="activity-table">
                      <b-table-simple bordered responsive>
                        <b-thead>
                          <b-tr>
                            <b-th>Subject</b-th>
                            <b-th>
                              Attendance
                              <span v-if="switchT">(%)</span>
                            </b-th>
                            <b-th>
                              Participation
                              <span v-if="switchT">(%)</span>
                            </b-th>
                            <b-th>
                              Assessment
                              <span v-if="switchT">(%)</span>
                            </b-th>
                            <b-th>
                              Examination
                              <span v-if="switchT">(%)</span>
                            </b-th>
                            <b-th   v-if="student.course_level=='tertiary'">Grade</b-th>
                          </b-tr>
                        </b-thead>
                        <b-tbody>
                          <b-tr v-for="(subject,idx) in subjects" :key="idx">
                            <b-td>
                              <div class="toCaps">{{subject.name}}</div>
                            </b-td>
                            <b-td>{{handleAttendances(subject.name) || '-'}}</b-td>
                            <b-td>{{handleParticipations(subject.name) || '-'}}</b-td>
                            <b-td>
                              <div>{{handleAss(subject.name) || '-'}}</div>
                            </b-td>
                            <b-td>
                              <div>{{handleExam(subject.name) || '-'}}</div>
                            </b-td>
                            <b-td 
                            v-if="student.course_level=='tertiary'"
                            class="toCaps">{{handleGrade(handleAttendances(subject.name),handleParticipations(subject.name),handleAss(subject.name),handleExam(subject.name))}}</b-td>
                          </b-tr>
                        </b-tbody>
                      </b-table-simple>
                    </div>
                  </div>
                </div>
              </b-tab>

              <b-tab title="Attendance / Participation">
                <div class="grade_book">
                  <h5 class="mb-5">Attendance/Participation</h5>
                  <div class="d-flex align-items-center mb-3 justify-content-end">
                    <label for="customSwitch1" class="m-0"></label>
                    <div class="custom-control custom-switch">
                      <input
                        type="checkbox"
                        class="custom-control-input"
                        id="customSwitch1"
                        v-model="switchT"
                      />
                      <label class="custom-control-label" for="customSwitch1">Percentage</label>
                    </div>
                  </div>
                  <b-row class="mb-3">
                    <b-col cols="12" sm="3">
                      <div class="text-right">
                        <b-form-select v-model="week" size="sm" class="week mb-2">
                          <b-form-select-option
                            :value="num"
                            v-for="(num,id) in n"
                            :key="id"
                          >{{getWeek(new Date())==num?'Current week ':'Week' }} {{num}}</b-form-select-option>
                        </b-form-select>
                      </div>
                    </b-col>
                    <b-col cols="12" sm="3">
                      <b-form-select size="sm" v-model="subject">
                        <b-form-select-option value>Subject</b-form-select-option>
                        <b-form-select-option
                          :value="item.name.toLowerCase()"
                          v-for="(item,idx) in subjects"
                          class="toCaps"
                          :key="idx"
                        >{{item.name}}</b-form-select-option>
                      </b-form-select>
                    </b-col>
                   
                  </b-row>
               

                  <b-table-simple bordered responsive>
                    <b-thead>
                      <b-tr>
                        <b-th>Subject</b-th>
                        <b-th>
                          Attendance
                          <span v-if="switchT">(%)</span>
                        </b-th>
                        <b-th>
                          Participation
                          <span v-if="switchT">(%)</span>
                        </b-th>
                      </b-tr>
                    </b-thead>
                    <b-tbody>
                      <b-tr v-for="(subject,idx) in sortSubject" :key="idx">
                        <b-td>
                          <div class="toCaps">{{subject.name}}</div>
                        </b-td>
                        <b-td>{{handleAttendance(subject.name)}}</b-td>
                        <b-td>{{handleParticipation(subject.name)}}</b-td>
                      </b-tr>
                    </b-tbody>
                  </b-table-simple>
                </div>
              </b-tab>

              <!-- <b-tab title="Participation">
                <div class="grade_book">
                  <h5 class="mb-5">Participation Grades</h5>
                  <b-row>
                    <b-col md="3">
                      <div class="text-right">
                        <b-form-select v-model="week" class="week mb-3">
                          <b-form-select-option
                            :value="num"
                            v-for="(num,id) in n"
                            :key="id"
                          >{{getWeek(new Date())==num?'Current week ':'Week' }} {{num}}</b-form-select-option>
                        </b-form-select>
                      </div>
                    </b-col>
                    <b-col md="3">
                      <b-form-select v-model="subject">
                        <b-form-select-option value>Subject</b-form-select-option>
                        <b-form-select-option
                          :value="item.name.toLowerCase()"
                          v-for="(item,idx) in subjects"
                          class="toCaps"
                          :key="idx"
                        >{{item.name}}</b-form-select-option>
                      </b-form-select>
                    </b-col>
                    <b-col cols="4" class="text-right">
                      <div>Week Score : {{handleParticipation() || 0}}/{{participation.length * 10|| 0}}</div>
                      <div>Week Percentage(%) : {{((handleParticipation() /(participation.length * 10)) * 100).toFixed(0)|| 0}}</div>
                    </b-col>
                  </b-row>
                  <b-table :items="participation" :fields="parts" bordered>
                    <template v-slot:cell(score)="data">
                      <div>{{data.item.participation_score}}</div>
                    </template>
                    <template v-slot:cell(day)="data">
                      <div>{{data.item.day}}</div>
                    </template>

                    <template v-slot:cell(sn)="data">
                      <div>{{data.index+1}}</div>
                    </template>
                  </b-table>
                </div>
              </b-tab>-->
            </b-tabs>
          </b-card>
        </b-col>
      </b-row>
    </b-container>
    <b-modal id="preview" size="lg" title="Result" hide-footer>
      <Preview :form="form" :options="options" />
    </b-modal>
  </div>
</template>
<script>
import Preview from "./preview";
export default {
  props: ["student"],
  data() {
    return {
      n: 52,
      participation_percent: 0.1,
      att_percent: 0.1,
      test_percent: 0.1,
      ass_percent: 0.1,
      quiz_percent: 0.1,
      exam_percent: 0.5,
      quiz: [],
      test: [],
      examination: [],
      assignment: [],
      week: null,
      transProps: {
        // Transition name
        name: "flip-list",
      },
      type: "assignment",
      overall: [],
      search: "",
      subjects: [],
      subject: "",
      ass_result: [],
      result: "",
      fields1: [
        { key: "subject", sortable: true },
        { key: "type", sortable: true },
        { key: "title", sortable: true },
        { key: "score", sortable: true },

        "percentage(%)",
      ],

      parts: ["sn", "subject", "score"],
      att: ["sn", "subject", "Attendance", "participation"],
      assessmentType: [
        { value: "", text: "All" },
        { value: "assignment", text: "Assignment" },
        { value: "quiz", text: "Quiz" },
        { value: "test", text: "Test" },
        { value: "exam", text: "Examination" },
      ],
      options: {},
      switchT: true,
      form: [],
      part: [],
      partsum: 0,
      attendances: [],
      sumAttendance: [],
      mobile: false,
      grading: [],
    };
  },
  components: {
    Preview,
  },
  computed: {
    sortSubject() {
      return this.subjects.filter((sub) => {
        return sub.name.toLowerCase().includes(this.subject.toLowerCase());
      });
    },
    participation() {
      var week = this.attendances.filter(
        (item) => this.getWeek(item.day) == this.week
      );
      return week.filter((item) =>
        item.subject.toLowerCase().includes(this.subject.toLowerCase())
      );
    },
    attendance() {
      var week = this.attendances.filter(
        (item) => this.getWeek(item.day) == this.week
      );
      return week.filter((item) =>
        item.subject.toLowerCase().includes(this.subject.toLowerCase())
      );
    },
    sortData() {
      var search = this.ass_result.filter((d) => {
        return d.title.toLowerCase().includes(this.search.toLowerCase());
      });

      var subject = search.filter((sub) => {
        return sub.subject.toLowerCase().includes(this.subject.toLowerCase());
      });
      var type = subject.filter((sub) => {
        return sub.type.toLowerCase().includes(this.type.toLowerCase());
      });
      return type;
    },
  },
  mounted() {
    this.getSub();
    this.getAss();
    this.getOverall();
    this.getPart();
    this.getAttendance();
    this.getData();
    this.getGrade();
    this.week = this.getWeek(new Date());

    if (window.innerWidth < 1024) {
      this.mobile = true;
    } else {
      this.mobile = false;
    }
  },
  methods: {
    handleGrade(att, part, ass, exam) {
       var total = (Number(att) + Number(part) + Number(ass) + Number(exam)) / 4;
    
      var grade;
      this.grading.find((item) => {
        if (total <= item.max && total >= item.min) {
          grade = item.grade;
        }
      });
      return grade || "-";
     
    },

    getGrade() {
      axios
        .get(`/api/grades-for-students`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          this.grading = res.data;
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
            this.items = res.data;
            this.items.forEach((item) => {
              if (item.type == "quiz") {
                this.quiz.push(item);
              }
              if (item.type == "test") {
                this.test.push(item);
              }
              if (item.type == "assignment") {
                this.assignment.push(item);
              }
              if (item.type == "exam") {
                this.examination.push(item);
              }
            });
          }
        })
        .catch((err) => {
          err.response.data;
          console.log(" err.response.data", err.response.data);
        });
    },
    handleExam(type) {
      var newarr = this.ass_result.filter((item) => item.type == "exam");
      var arr = [];
      newarr.forEach((item) => {
        if (item.subject.toLowerCase() == type.toLowerCase()) {
          let data = {
            score: Number(item.total_score),
            overall: Number(item.overall),
          };

          arr.push(data);
        }
      });
      if (arr.length) {
        if (this.switchT) {
          return (arr[0].score / arr[0].overall) * 100;
        } else {
          return `${arr[0].score}/${arr[0].overall}`;
        }
      } else {
        return 0;
      }
    },
    handleAss(type) {
      var arr = [];
      var newarr = this.ass_result.filter((item) => item.type !== "exam");
      var tot_score = [];
      var ove_scor = [];

      newarr.forEach((item) => {
        if (item.subject.toLowerCase() == type.toLowerCase()) {
          tot_score.push(Number(item.total_score));
          ove_scor.push(Number(item.overall));
          arr.push((item.total_score / item.overall) * 100);
        }
      });

      var sumtot = tot_score.reduce((a, b) => {
        return a + b;
      }, 0);
      var sumove = ove_scor.reduce((a, b) => {
        return a + b;
      }, 0);

      var sum = arr.reduce((a, b) => {
        return a + b;
      }, 0);

      if (this.switchT) {
        return Math.round((sumtot / sumove) * 100) || 0;
      } else {
        return `${sumtot}/${sumove}`;
      }
    },

    handleParticipations(type) {
      var newarr = [];
      if (this.attendances.length) {
        this.attendances.forEach((item) => {
          if (
            item.user_id == this.$props.student.id &&
            item.subject.toLowerCase() == type.toLowerCase()
          ) {
            newarr.push(item.participation_score);
          }
        });

        var sum = newarr.reduce((a, b) => {
          return a + b;
        }, 0);
        if (this.switchT) {
          return Math.round((sum / (newarr.length * 10)) * 100) || 0;
        } else {
          return `${sum}/${newarr.length * 10}`;
        }
      }
    },
    handleAttendances(type) {
      var newarr = [];
      var arr = [];
      this.attendances.forEach((item) => {
        if (
          item.user_id == this.$props.student.id &&
          item.subject.toLowerCase() == type.toLowerCase()
        ) {
          arr.push(item.score);
        }
      });

      var sum = arr.reduce((a, b) => {
        return a + b;
      }, 0);

      // perecentage view

      if (this.switchT) {
        return Math.round((sum / arr.length) * 100) || 0;
      } else {
        return `${sum}/${arr.length}`;
      }
    },
    handleParticipation(type) {
      var newarr = [];
      if (this.attendance.length) {
        this.attendance.forEach((item) => {
          if (
            item.user_id == this.$props.student.id &&
            item.subject.toLowerCase() == type.toLowerCase()
          ) {
            newarr.push(item.participation_score);
          }
        });

        var sum = newarr.reduce((a, b) => {
          return a + b;
        }, 0);

        if (this.switchT) {
          return Math.round((sum / (newarr.length * 10)) * 100) || 0;
        } else {
          return `${sum}/${newarr.length * 10}`;
        }
      } else {
        return 0;
      }
    },
    handleAttendance(type) {
      var newarr = [];
      var arr = [];
      this.attendance.forEach((item) => {
        if (
          item.user_id == this.$props.student.id &&
          item.subject.toLowerCase() == type.toLowerCase()
        ) {
          arr.push(item.score);
        }
      });

      var sum = arr.reduce((a, b) => {
        return a + b;
      }, 0);

      // perecentage view

      if (this.switchT) {
        return Math.round((sum / arr.length) * 100) || 0;
      } else {
        return `${sum}/${arr.length}`;
      }
    },
    preview(val) {
      this.options.title = val.title;
      this.options.level = val.level;
      this.options.subject = val.subject;
      this.options.total_score = val.total_score;
      this.options.overall = val.overall;
      this.form = JSON.parse(val.record);
      this.$bvModal.show("preview");
    },
    refresh() {
      this.subject = this.search = this.type = "";
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
            this.subjects = res.data;
          }
        });
    },
    getAss() {
      axios
        .get(`/api/get-assessment-result`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          this.ass_result = res.data;
        });
    },
    getPart() {
      axios
        .get(`/api/student-part`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          this.part = res.data;
          let score = [];
          res.data.forEach((item) => {
            score.push(item.score);
          });
          this.partsum = score.reduce((a, b) => {
            return a + b;
          }, 0);
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
            this.attendances = res.data;
          }
        });
    },
    getOverall() {
      axios
        .get(`/api/student-grade-book/${this.$props.student.id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          this.overall = res.data;
        });
    },
  },
};
</script>

<style scoped>
.container {
  padding-top: 50px;
  padding-bottom: 70px;
}

.grade_book {
  max-height: 80vh;
}
::-webkit-scrollbar {
  width: 5px;
}

/* Track */
::-webkit-scrollbar-track {
  background: none;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: #c4c4c4;
  border-radius: 5px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555;
}
@media screen and (max-width: 600px) {
  .activity-table {
    overflow: scroll;
    padding-top: 24px;
  }
  th {
    font-size: 14px;
    text-align: center;
    min-width:150px;
  }
  td{
    font-size:14px;
  }
}
</style>