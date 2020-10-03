<template>
  <div>
    <b-modal id="single" size="lg" hide-footer :title="myid.name" v-if="myid">
       <div class="d-flex align-items-center mb-3 justify-content-end p-2">
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
      <b-table-simple bordered>
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
            <b-th v-if="JSON.parse(admin.level).includes('tertiary')">Grade</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <b-tr v-for="(subject,idx) in subjects" :key="idx">
            <b-td>
              <div class="toCaps">{{subject.name}}</div>
            </b-td>
            <b-td>{{handleAttendance(myid.id,subject.name)}}</b-td>
            <b-td>{{handleParticipation(myid.id,subject.name)}}</b-td>
            <b-td>
              <div>{{handleAsss(myid.id,subject.name) || 0}}</div>
            </b-td>
            <b-td>
              <div>{{handleExams(myid.id,subject.name)}}</div>
            </b-td>
            <!-- <b-td 
                            v-if="JSON.parse(admin.level).includes('tertiary')"
            class="toCaps">{{handleGrade(handleAttendances(subject.name),handleParticipations(subject.name),handleAss(subject.name),handleExam(subject.name))}}</b-td>-->
          </b-tr>
        </b-tbody>
      </b-table-simple>
    </b-modal>
    <div class="outer-grade-book container">
      <b-card class no-body>
        <b-tabs card justified>
          <b-tab title="Overall Grades" class="overall">
            <div class="grade-book">
              <div class="sort-table">
                <div class="text-right mb-2">
                  <div class="export">
                    <div class="btn btn-export" @click="generatePdf">
                      Export
                      <i class="fa fa-external-link"></i>
                    </div>
                  </div>
                </div>

                <b-container>
                  <b-row>
                    <b-col class="mb-2" cols="12" md="5">
                      <b-form-input
                       size="sm"
                        placeholder="Search Student name"
                        v-model="overall_search"
                        class="search rounded-pill"
                      ></b-form-input>
                    </b-col>
                    <b-col md="3">
                      <b-form-select  size="sm" v-model="my_class">
                        <b-form-select-option
                          :value="item.class_name.toLowerCase()"
                          v-for="(item,idx) in all_class"
                          class="toCaps"
                          :key="idx"
                        >{{item.class_name}}</b-form-select-option>
                      </b-form-select>
                    </b-col>
                  </b-row>
                </b-container>
              </div>
            </div>
            <hr />
            <div class="overallgradebook-table" ref="getCsv">
              <vue-html2pdf
                :show-layout="false"
                :enable-download="true"
                :preview-modal="false"
                :paginate-elements-by-height="1400"
                filename="Report"
                :pdf-quality="2"
                :manual-pagination="false"
                pdf-format="a4"
                pdf-orientation="portrait"
                pdf-content-width="800px"
                @progress="onProgress($event)"
                @hasStartedGeneration="hasStartedGeneration()"
                @hasGenerated="hasGenerated($event)"
                ref="html2Pdf"
              >
                <section slot="pdf-content">
                  <div class="activity-table">
                    <b-table-simple hover class="table-bordered">
                      <b-thead>
                        <b-tr class="gradebook-table">
                          <b-th>Name</b-th>
                          <b-th>
                            <div>Attendance(%)</div>
                          </b-th>
                          <b-th>
                            <div>Participation(%)</div>
                          </b-th>
                          <b-th v-for="(subject,idx) in subjects" :key="idx">
                            <div>
                              {{subject.name}}(%)
                              <br />
                            </div>
                          </b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(stud,idx) in sortedStud" :key="idx">
                          <b-td>{{stud.name}}</b-td>
                          <b-td>{{handleAttendance(stud.id) || 0}}</b-td>
                          <b-td>{{handleParticipation(stud.id) || 0}}</b-td>
                          <b-td
                            v-for="(subject,idx) in subjects"
                            :key="idx"
                          >{{(handleAss(stud.id,subject.name) || 0)}}</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </div>
                </section>
              </vue-html2pdf>
              <div class="d-flex align-items-center mb-3 justify-content-end p-2">
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
              <b-table-simple hover responsive class="table-bordered" >
                <b-thead>
                  <b-tr class="gradebook-table">
                    <b-th>Name</b-th>

                    <b-th v-for="(subject,idx) in subjects" :key="idx">
                      <div>
                        <p class="mr-3 toCaps">{{subject.name}}</p>
                        <p class="extra">Attendance (%)</p>
                        <p class="extra">Participation (%)</p>
                        <p class="extra">Assessment (%)</p>
                        <p class="extra">Examination(%)</p>
                      </div>
                    </b-th>
                  </b-tr>
                </b-thead>
                <b-tbody>
                  <b-tr
                    @click="showgrade(stud)"
                    class="cpointer"
                    v-for="(stud,idx) in sortedStud"
                    :key="idx"
                  >
                    <b-td>{{stud.name}}</b-td>

                    <b-td v-for="(subject,idx) in subjects" :key="idx">
                      <span class="d-flex justify-content-center">
                        <p></p>
                        <p>{{handleAttendance(stud.id, subject.name) || 0}}</p>
                        <p>{{handleParticipation(stud.id, subject.name) || 0}}</p>
                        <p>{{(handleAss(stud.id,subject.name)|| 0)}}</p>
                        <p>{{(handleExam(stud.id,subject.name)|| 0)}}</p>
                      </span>
                    </b-td>
                  </b-tr>
                </b-tbody>
              </b-table-simple>
            </div>
          </b-tab>
          <b-tab title="Assessment Grades">
            <div class="grade-book">
              <div class="sort-table">
                <b-container>
                  <b-row>
                    <b-col class="mb-3" md="8">
                      <b-container>
                        <b-row>
                          <b-col md="3">
                            <b-form-select size="sm" v-model="my_class">
                              <b-form-select-option
                                :value="item.class_name.toLowerCase()"
                                v-for="(item,idx) in all_class"
                                class="toCaps"
                                :key="idx"
                              >{{item.class_name}}</b-form-select-option>
                            </b-form-select>
                          </b-col>

                          <b-col md="4">
                            <b-form-select size="sm" v-model="type" :options="assessmentType"></b-form-select>
                          </b-col>
                          <b-col md="4">
                            <b-form-select  size="sm" v-model="subject">
                              <b-form-select-option value>Select subject</b-form-select-option>
                              <b-form-select-option
                                :value="item.name.toLowerCase()"
                                v-for="(item,idx) in subjects"
                                class="toCaps"
                                :key="idx"
                              >{{item.name}}</b-form-select-option>
                            </b-form-select>
                          </b-col>

                          <b-col md="1">
                            <span @click="refresh">
                              <i class="fa fa-refresh" aria-hidden="true"></i>
                            </span>
                          </b-col>
                        </b-row>
                      </b-container>
                    </b-col>
                    <b-col md="4">
                      <b-form-input
                       size="sm"
                        placeholder="Search Student"
                        v-model="search"
                        class="search rounded-pill"
                      ></b-form-input>
                    </b-col>
                  </b-row>
                </b-container>
              </div>
              <hr />
              <div class="gradebook-table">
                <b-table
                v-if="ass.length"
                  striped
                  hover
                  sticky-header
                  responsive
                  bordered
                  :fields="assessmentField"
                  :items="ass"
                ></b-table>
                <b-alert show v-else>No Data</b-alert>
              </div>
            </div>
          </b-tab>
        </b-tabs>
      </b-card>
    </div>
  </div>
</template>

<script>
import VueHtml2pdf from "vue-html2pdf";
export default {
  props: ["admin"],
  data() {
    return {
      myid: {},
      particpation_percent: 0,
      att_percent: 0,
      test_percent: 0,
      ass_percent: 0,
      quiz_percent: 0,
      exam_percent: 0,
      assignment: [],
      examination: [],
      quiz: [],
      test: [],
      overall_search: "",
      search: "",
      subject: "",
      my_class: "",
      subjects: [],
      type: "",
      all_class: [],
      selected: null,
      items: [],
      classes: [],
      subjects: [],
      subject: "",
      name: "",
      result: [],
      assessmentField: ["name"],
      field1: ["class_name"],
      fields: { Participation: "participate", Attendance: "attendance" },
      items: [
        { participate: "Joe", attendance: 33 },
        { participate: "Sue", attendance: 77 },
      ],

      field2: ["name"],
      overalltableitems: [
        {
          name: "success ahon",
          participation: 20,
          attendance: 10,
          quiz: 20,
          assignment: 20,
          test: 20,
          examination: 60,
          total: 80,
        },
        {
          name: "success ahon",
          participation: 20,
          attendance: 10,
          quiz: 20,
          assignment: 20,
          test: 20,
          examination: 60,
          total: 80,
        },
      ],

      assessmentType: [
        { value: "", text: " Select Assessment" },
        { value: "assignment", text: "Assignment" },
        { value: "quiz", text: "Quiz" },
        { value: "test", text: "Test" },
        { value: "examination", text: "Examination" },
      ],

      ass: [],
      names: [],
      grade_book: [],
      grade_fields: [
        { key: "name", sortable: true },
        "participation",
        "attendance",
        "quiz",
        "assignment",
        "test",
        "examination",
        // "total_score",
      ],
      attendance: [],
      participations: [],
      allstudnts: [],
      switchT: true,
    };
  },
  components: {
    VueHtml2pdf,
  },

  created() {
    this.getStuds();
    this.getBook();
    this.getData();
    this.getClasses();
    this.getSubjects();
    this.getResult();
    this.getParticipation(), this.getAttendance();
  },
  mounted() {},
  computed: {
    sortedStud() {
      var search = this.allstudnts.filter((item) =>
        item.name.toLowerCase().includes(this.overall_search.toLowerCase())
      );
      return search.filter((item) =>
        item.student_level
          .toLowerCase()
          .includes(this.my_class.toLowerCase().trim())
      );
    },
    sortData() {
      var search = this.result.filter((d) => {
        return d.user.name.toLowerCase().includes(this.search.toLowerCase());
      });
      var level = search.filter((sub) => {
        return sub.level.toLowerCase().includes(this.my_class.toLowerCase());
      });

      var subject = level.filter((sub) => {
        return sub.subject.toLowerCase().includes(this.subject.toLowerCase());
      });
      var type = subject.filter((sub) => {
        return sub.type.toLowerCase().includes(this.type.toLowerCase());
      });
      return type;
    },
    sortGrades() {
      var search = this.grade_book.filter((d) => {
        return d.user.name
          .toLowerCase()
          .includes(this.overall_search.toLowerCase());
      });
      var level = search.filter((sub) => {
        return sub.level.toLowerCase().includes(this.my_class.toLowerCase());
      });

      //    var subject = level.filter(sub=>{
      //   return  sub.subject.toLowerCase().includes(this.subject.toLowerCase())
      //   })
      //  var type = subject.filter(sub=>{
      //   return  sub.type.toLowerCase().includes(this.type.toLowerCase())
      //   })
      return level;
    },
  },
  watch: {
    sortData(newValue) {
      this.handleData(newValue);
    },
  },

  methods: {
    showgrade(id) {
    
      this.myid = id;
      this.$bvModal.show("single");
    },
    handleExams(id, type) {
      var newarr = this.items.filter(
        (item) => item.type == "exam" && item.user_id == id.id
      );
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
    handleAsss(id, type) {
      var arr = [];
      var newarr = this.items.filter(
        (item) => item.type !== "exam" && item.user_id == id.id
      );
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

    handleParticipations(id, type) {
      var newarr = [];
      if (this.attendance.length) {
        this.attendance.forEach((item) => {
          if (
            item.user_id == id.id &&
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
    handleAttendances(id, type) {
      var newarr = [];
      var arr = [];
      this.attendance.forEach((item) => {
        if (
          item.user_id == id.id &&
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

    onProgress($event) {},
    hasStartedGeneration() {},
    hasGenerated($event) {},
    generatePdf() {
      this.$refs.html2Pdf.generatePdf();
    },
    handleExam(id, type) {
      var newarr = this.items.filter((item) => item.type == "exam" && item.user.id == id);
     
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
    handleAss(id, type) {
      var arr = [];
      var newarr = this.items.filter((item) => item.type !== "exam" && item.user.id == id);
     
      var tot_score = [];
      var ove_scor = [];
      newarr.forEach((item) => {
       if (item.user) {
          if (item.user.id == id) {
          if (item.subject.toLowerCase() == type.toLowerCase()) {
            (item.total_score / item.overall) * 100;

            tot_score.push(Number(item.total_score));
            ove_scor.push(Number(item.overall));
            arr.push((item.total_score / item.overall) * 100);
          }
        }
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
    handleParticipation(id, type) {
      var newarr = [];
      this.attendance.forEach((item) => {
        if (item.user_id == id) {
          if (type) {
            if (item.subject.toLowerCase() == type.toLowerCase()) {
              newarr.push(item.participation_score);
            }
          }
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
    },
    handleAttendance(id, type) {
      var newarr = [];
      var arr = [];
      this.attendance.forEach((item) => {
        if (item.user_id == id) {
          if (type) {
            if (item.subject.toLowerCase() == type.toLowerCase()) {
              arr.push(item.score);
            }
          }
        }
      });

      var sum = arr.reduce((a, b) => {
        return a + b;
      }, 0);
      if (this.switchT) {
        return Math.round((sum / arr.length) * 100) || 0;
      } else {
        return `${sum}/${arr.length}`;
      }
    },
    getParticipation() {
      let admin = JSON.parse(localStorage.getItem("typeAdmin"));
      axios
        .get("/api/admin-participation", {
          headers: {
            Authorization: `Bearer ${admin.access_token}`,
          },
        })
        .then((res) => {
          this.participations = res.data;
        });
    },
    getAttendance() {
      let admin = JSON.parse(localStorage.getItem("typeAdmin"));
      axios
        .get(`/api/admin-get-attendance`, {
          headers: {
            Authorization: `Bearer ${admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.attendance = res.data;
          }
        });
    },
    refresh() {
      this.subject = this.my_class = this.search = this.type = "";
    },
    getSubjects() {
      let admin = JSON.parse(localStorage.getItem("typeAdmin"));
      axios
        .get("/api/subject", {
          headers: {
            Authorization: `Bearer ${admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.subjects = res.data;
          }
        });
    },
    getClasses() {
      let admin = JSON.parse(localStorage.getItem("typeAdmin"));
      axios
        .get("/api/classes", {
          headers: {
            Authorization: `Bearer ${admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.all_class = res.data;
            this.my_class = res.data[0].class_name.toLowerCase();
          }
        });
    },
    handleData(arr) {
      var obj = {};
      this.ass = [];
      this.assessmentField = ["name"];
      arr.forEach((item) => {
        this.assessmentField.push(item.title);
        if (this.ass.length == 0) {
          obj[item.title] = item.total_score + "/" + item.overall;

          obj.name = item.user.name;

          this.ass.push(obj);
        } else {
          this.ass.forEach((ite, index) => {
            obj = {};
            if (Object.values(ite).includes(item.user.name)) {
              ite[item.title] = item.total_score + "/" + item.overall;
            } else {
              obj.name = item.user.name;

              return obj;
            }
            return obj;
          });

          if (Object.keys(obj).length !== 0) {
            this.ass.push(obj);
          }
        }
      });
    },
    getResult() {
      let admin = JSON.parse(localStorage.getItem("typeAdmin"));
      axios
        .get("/api/get-admin-assessment", {
          headers: {
            Authorization: `Bearer ${admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.result = res.data;
            this.result.sort(function (a, b) {
              return a.user_id - b.user_id;
            });

            // this.handleData()
          }
        });
    },

    getData() {
      let admin = JSON.parse(localStorage.getItem("typeAdmin"));

      axios
        .get(`/api/admin-all-result`, {
          headers: {
            Authorization: `Bearer ${admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.items = res.data.data;
          }
        })
        .catch();
    },
    selectClass(name) {
      this.name = name;
    },
    selectSubject(name) {
      this.subject = name;
    },
    getBook() {
      let admin = JSON.parse(localStorage.getItem("typeAdmin"));

      axios
        .get(`/api/admin-grade-books`, {
          headers: { Authorization: `Bearer ${admin.access_token}` },
        })
        .then((res) => {
          if (res.status == 200) {
            this.grade_book = res.data;
          }
        });
    },
    getStuds() {
      let admin = JSON.parse(localStorage.getItem("typeAdmin"));
      axios
        .get("/api/admin-get-students", {
          headers: {
            Authorization: `Bearer ${admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.allstudnts = res.data;
            this.myid = res.data[0];
          }
        });
    },
  },
};
</script>
<style scoped>
.overallgradebook-table {
  font-family: "Montserrat";
}
.gradebook-table {
  font-family: "Montserrat";
}

.grade-book {
  background: #fff;
  padding: 15px 0;
}

.overall {
  background: #fff;
}
.outer-grade-book {
  padding-top: 20px;
}
.search {
  /* width:250px; */
  border-color: #41cee2;
}
.sort-table p {
  font-size: 14px;
}
.btn-export {
  border: 1px solid #c4c4c4;
}
.b-table-sticky-header {
  max-height: 80vh;
}
/* width */
::-webkit-scrollbar {
  width: 5px;
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
.extra {
  font-weight: normal;
}
p {
  margin-left: 1rem;
}
.table-bordered {
  border: 1px solid #dee2e6;
}
</style>