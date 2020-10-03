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
          <label class="custom-control-label" for="customSwitch1"
            >Percentage</label
          >
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
            <!-- <b-th v-if="JSON.parse(admin.level).includes('tertiary')">Grade</b-th> -->
          </b-tr>
        </b-thead>
        <b-tbody>
          <b-tr v-for="(subject, idx) in subjects" :key="idx">
            <b-td>
              <div class="toCaps">{{ subject.name }}</div>
            </b-td>
            <b-td>{{ handleAttendance(myid.id, subject.name) }}</b-td>
            <b-td>{{ handleParticipation(myid.id, subject.name) }}</b-td>
            <b-td>
              <div>{{ handleAss(myid.id, subject.name) || 0 }}</div>
            </b-td>
            <b-td>
              <div>{{ handleExam(myid.id, subject.name) }}</div>
            </b-td>
            <!-- <b-td 
                            v-if="JSON.parse(admin.level).includes('tertiary')"
            class="toCaps">{{handleGrade(handleAttendances(subject.name),handleParticipations(subject.name),handleAss(subject.name),handleExam(subject.name))}}</b-td>-->
          </b-tr>
        </b-tbody>
      </b-table-simple>
    </b-modal>
    <div class="outer-grade-book container">
      <b-card class="tutor" no-body>
        <b-tabs card justified>
          <b-tab title="Overall Grades" class="overall">
            <div class="grade-book">
              <div class="sort-table">
                <div class="text-right mb-3">
                  <div class="export">
                    <div class="btn btn-export btn-sm" @click="generatePdf">
                      Export
                      <i class="fa fa-external-link"></i>
                    </div>
                  </div>
                </div>

                <b-container>
                  <b-row>
                    <b-col class="mb-2" cols="8" md="5">
                      <b-form-input
                      size="sm"
                        placeholder="Search Student name"
                        v-model="overall_search"
                        class="search rounded-pill"
                      ></b-form-input>
                    </b-col>
                    <b-col cols="4" md="3">
                      <b-form-select size="sm" v-model="my_class">
                        <b-form-select-option
                          :value="item.class_name.toLowerCase()"
                          v-for="(item, idx) in all_class"
                          class="toCaps"
                          :key="idx"
                          >{{ item.class_name }}</b-form-select-option
                        >
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
                    <div v-if="!tutor.head">
                <b-table-simple
                  hover
                  responsive
                  class="table-bordered"
                  v-if="tutorClassSubject.length"
                >
                  <b-thead>
                    <b-tr class="gradebook-table">
                      <b-th>Name</b-th>

                      <b-th
                        v-for="(subject, idx) in tutorClassSubject"
                        :key="idx"
                      >
                        <div>
                          <p class="mr-3 toCaps">{{ subject }}</p>
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
                      class="cpointer"
                      :id="'mygrade' + idx"
                      @click="showgrade(stud)"
                      v-for="(stud, idx) in sortedStud"
                      :key="idx"
                    >
                      <b-tooltip
                        :target="'mygrade' + idx"
                        triggers="hover"
                        placement="top"
                        >Click to view</b-tooltip
                      >
                      <b-td>{{ stud.name }}</b-td>
                      <!-- <b-td>{{handleAttendance(stud.id) || 0}}</b-td>
                    <b-td>{{handleParticipation(stud.id) || 0}}</b-td>-->
                      <b-td
                        v-for="(subject, idx) in tutorClassSubject"
                        :key="idx"
                      >
                        <span class="d-flex justify-content-center">
                          <p></p>
                          <p>{{ handleAttendance(stud.id, subject) || 0 }}</p>
                          <p>
                            {{ handleParticipation(stud.id, subject) || 0 }}
                          </p>
                          <p>{{ handleAss(stud.id, subject) || 0 }}</p>
                          <p>{{ handleExam(stud.id, subject) || 0 }}</p>
                        </span>
                      </b-td>
                    </b-tr>
                  </b-tbody>
                </b-table-simple>
                <b-alert show v-else>No data available</b-alert>
              </div>

              <div v-else>
                <b-table-simple
                  hover
                  responsive
                  class="table-bordered"
                  v-if="getClassSubject.length"
                >
                  <b-thead>
                    <b-tr class="gradebook-table">
                      <b-th>Name</b-th>

                      <b-th
                        v-for="(subject, idx) in getClassSubject"
                        :key="idx"
                      >
                        <div>
                          <p class="mr-3 toCaps">{{ subject }}</p>
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
                      :id="'mygrade' + idx"
                      class="cpointer"
                      @click="showgrade(stud)"
                      v-for="(stud, idx) in sortedStud"
                      :key="idx"
                    >
                      <b-tooltip
                        :target="'mygrade' + idx"
                        triggers="hover"
                        placement="top"
                        >Click to view</b-tooltip
                      >
                      <b-td>{{ stud.name }}</b-td>
                      <!-- <b-td>{{handleAttendance(stud.id) || 0}}</b-td>
                    <b-td>{{handleParticipation(stud.id) || 0}}</b-td>-->
                      <b-td
                        v-for="(subject, idx) in getClassSubject"
                        :key="idx"
                      >
                        <span class="d-flex justify-content-center">
                          <p></p>
                          <p>{{ handleAttendance(stud.id, subject) || 0 }}</p>
                          <p>
                            {{ handleParticipation(stud.id, subject) || 0 }}
                          </p>
                          <p>{{ handleAss(stud.id, subject) || 0 }}</p>
                          <p>{{ handleExam(stud.id, subject) || 0 }}</p>
                        </span>
                      </b-td>
                    </b-tr>
                  </b-tbody>
                </b-table-simple>

                <b-alert show v-else>No data available</b-alert>
              </div>
                  </div>
                </section>
              </vue-html2pdf>
              <div
                class="d-flex align-items-center mb-3 justify-content-end p-2"
              >
                <label for="customSwitch1" class="m-0"></label>
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    id="customSwitch1"
                    v-model="switchT"
                  />
                  <label class="custom-control-label" for="customSwitch1"
                    >Percentage</label
                  >
                </div>
              </div>
              <div v-if="!tutor.head">
                <b-table-simple
                  hover
                  responsive
                  class="table-bordered"
                  v-if="tutorClassSubject.length"
                >
                  <b-thead>
                    <b-tr class="gradebook-table">
                      <b-th>Name</b-th>

                      <b-th
                        v-for="(subject, idx) in tutorClassSubject"
                        :key="idx"
                      >
                        <div>
                          <p class="mr-3 toCaps">{{ subject }}</p>
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
                      class="cpointer"
                      :id="'mygrade' + idx"
                      @click="showgrade(stud)"
                      v-for="(stud, idx) in sortedStud"
                      :key="idx"
                    >
                      <b-tooltip
                        :target="'mygrade' + idx"
                        triggers="hover"
                        placement="top"
                        >Click to view</b-tooltip
                      >
                      <b-td>{{ stud.name }}</b-td>
                      <!-- <b-td>{{handleAttendance(stud.id) || 0}}</b-td>
                    <b-td>{{handleParticipation(stud.id) || 0}}</b-td>-->
                      <b-td
                        v-for="(subject, idx) in tutorClassSubject"
                        :key="idx"
                      >
                        <span class="d-flex justify-content-center">
                          <p></p>
                          <p>{{ handleAttendance(stud.id, subject) || 0 }}</p>
                          <p>
                            {{ handleParticipation(stud.id, subject) || 0 }}
                          </p>
                          <p>{{ handleAss(stud.id, subject) || 0 }}</p>
                          <p>{{ handleExam(stud.id, subject) || 0 }}</p>
                        </span>
                      </b-td>
                    </b-tr>
                  </b-tbody>
                </b-table-simple>
                <b-alert show v-else>No data available</b-alert>
              </div>

              <div v-else>
                <b-table-simple
                  hover
                  responsive
                  class="table-bordered"
                  v-if="getClassSubject.length"
                >
                  <b-thead>
                    <b-tr class="gradebook-table">
                      <b-th>Name</b-th>

                      <b-th
                        v-for="(subject, idx) in getClassSubject"
                        :key="idx"
                      >
                        <div>
                          <p class="mr-3 toCaps">{{ subject }}</p>
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
                      :id="'mygrade' + idx"
                      class="cpointer"
                      @click="showgrade(stud)"
                      v-for="(stud, idx) in sortedStud"
                      :key="idx"
                    >
                      <b-tooltip
                        :target="'mygrade' + idx"
                        triggers="hover"
                        placement="top"
                        >Click to view</b-tooltip
                      >
                      <b-td>{{ stud.name }}</b-td>
                      <!-- <b-td>{{handleAttendance(stud.id) || 0}}</b-td>
                    <b-td>{{handleParticipation(stud.id) || 0}}</b-td>-->
                      <b-td
                        v-for="(subject, idx) in getClassSubject"
                        :key="idx"
                      >
                        <span class="d-flex justify-content-center">
                          <p></p>
                          <p>{{ handleAttendance(stud.id, subject) || 0 }}</p>
                          <p>
                            {{ handleParticipation(stud.id, subject) || 0 }}
                          </p>
                          <p>{{ handleAss(stud.id, subject) || 0 }}</p>
                          <p>{{ handleExam(stud.id, subject) || 0 }}</p>
                        </span>
                      </b-td>
                    </b-tr>
                  </b-tbody>
                </b-table-simple>

                <b-alert show v-else>No data available</b-alert>
              </div>
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
                                v-for="(item, idx) in all_class"
                                class="toCaps"
                                :key="idx"
                                >{{ item.class_name }}</b-form-select-option
                              >
                            </b-form-select>
                          </b-col>

                          <b-col md="4">
                            <b-form-select
                            size="sm"
                              v-model="type"
                              :options="assessmentType"
                            ></b-form-select>
                          </b-col>
                          <b-col md="4">
                            <b-form-select
                            size="sm"
                              class="toCaps"
                              v-model="subject"
                              v-if="tutor.head"
                            >
                              <b-form-select-option value="select"
                                >Select subject</b-form-select-option
                              >
                              <b-form-select-option
                                :value="item.toLowerCase()"
                                v-for="(item, idx) in tutor.head?getClassSubject:tutorClassSubject"
                                class="toCaps"
                                :key="idx"
                                >{{ item }}</b-form-select-option
                              >
                            </b-form-select>

                            

                            <b-form-select
                            size="sm"
                              class="toCaps"
                              v-model="subject"
                              v-else
                            >
                              <b-form-select-option value="select"
                                >Select subject</b-form-select-option
                              >
                              <b-form-select-option
                                :value="item.toLowerCase()"
                                v-for="(item, idx) in tutor.courses"
                                class="toCaps"
                                :key="idx"
                                >{{ item }}</b-form-select-option
                              >
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
                  <b-alert show v-else>No data available</b-alert>
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
  props: ["tutor"],
  data() {
    return {
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
      subject: "select",
      my_class: "",
      subjects: [],
      type: "",
      all_class: [],
      selected: null,
      items: [],
      classes: [],
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
      myid: {},
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
  // mounted() {
  //   this.recordTimeline("logged");
  // },
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
    getClassSubject() {
      if (this.all_class.length) {
        var value = this.all_class.find(
          (item) => item.class_name.toLowerCase() == this.my_class.toLowerCase()
        );
        if (value.classsubject) {
          return JSON.parse(value.classsubject.subjects);
        }
      }
      return [];
    },
    tutorClassSubject() {
      var res = [];

      if (this.getClassSubject.length) {
        this.getClassSubject.forEach((item) => {
          if (this.$props.tutor.courses.includes(item.toLowerCase())) {
            res.push(item);
          }
        });
      }
      return res;
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
    onProgress($event) {},
    hasStartedGeneration() {},
    hasGenerated($event) {},
    generatePdf() {
      this.$refs.html2Pdf.generatePdf();
    },
    handleExam(id, type) {
      var newarr = this.items.filter(
        (item) => item.type == "exam" && item.user.id == id
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
    handleAss(id, type) {
      var arr = [];
      var newarr = this.items.filter(
        (item) => item.type !== "exam" && item.user.id == id
      );
      var tot_score = [];
      var ove_scor = [];
      newarr.forEach((item) => {
        if (item.subject.toLowerCase() == type.toLowerCase()) {
          (item.total_score / item.overall) * 100;

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
        if (item.user) {
          if (item.user.id == id) {
            if (type) {
              if (item.subject.toLowerCase() == type.toLowerCase()) {
                arr.push(item.score);
              }
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
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      axios
        .get("/api/participation", {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          this.participations = res.data;
        });
    },
    getAttendance() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      axios
        .get(`/api/tutor-get-attendance`, {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.attendance = res.data;
          }
        });
    },
    refresh() {
    this.my_class = this.search = this.type = "";
    },
    getSubjects() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      axios
        .get(`/api/tutor-all-subjects`, {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.subjects = res.data;
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
            this.all_class = res.data;
            this.my_class = res.data[0].class_name.toLowerCase();
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
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      axios
        .get("/api/get-tutor-assessment", {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
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
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));

      axios
        .get(`/api/all-result`, {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
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
      // this.subject = name;
    },
    getBook() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));

      axios
        .get(`/api/tutor-grade-books`, {
          headers: { Authorization: `Bearer ${tutor.access_token}` },
        })
        .then((res) => {
          if (res.status == 200) {
            this.grade_book = res.data;
          }
        });
    },
    getStuds() {
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
.activity-table {
  overflow: scroll;
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
  background: #555;
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
</style>