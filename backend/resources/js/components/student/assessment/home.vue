<template>
  <b-container>
    <b-card no-body class="assess">
      <b-tabs card justified>
        <b-tab title="Assignment" active>
          <b-row class="justify-content-end my-5">
            <b-col cols="12" sm="3" class="mb-2">
              <b-form-select size="sm" class="toCaps" v-model="mysubject">
               
                    <b-form-select-option :value="item.name" v-for="(item,idx)  in subjects"  :key="idx">{{item.name}}</b-form-select-option>
              </b-form-select>
            </b-col>
            <b-col cols="12" sm="6" class="mb-2">
              <b-input-group>
                <b-form-input v-model="search" size="sm" placeholder="Search title"></b-form-input>
                <b-input-group-append>
                  <div class="create-btn ">
                    <div class="btn btn-create btn-sm">Search</div>
                  </div>
                </b-input-group-append>
              </b-input-group>
            </b-col>
          </b-row>
          <b-card-text>
            <b-table   responsive :fields="fields" :items="sortedAss" bordered v-if="sortedAss.length">
              <template v-slot:cell(Sn)="data">{{data.index+1}}</template>
              <template v-slot:cell(action)="data">
                <span @click="checkPresence(data.item)" class="cpointer">View</span>
              </template>
              <template v-slot:cell(title)="data">
                <div class="main-title">
                  <div class="title">{{JSON.parse(data.item.option).title}}</div>
                  <div class="text-muted">{{JSON.parse(data.item.option).description}}</div>
                </div>
              </template>
              <template v-slot:cell(deadline)="data">
                <span>{{new Date(data.item.end)| moment('DD MMM, hh:mm A')}}</span>
              </template>
              <template v-slot:cell(status)="data">
                <span>{{data.item.end |timeStatus}}</span>
              </template>
            </b-table>
            <b-alert v-else show>No data</b-alert>
          </b-card-text>
        </b-tab>
        <b-tab title="Quiz">
          <b-row class="justify-content-end my-5">
            <b-col cols="12" sm="3" class="mb-2">
              <b-form-select  size="sm" class="toCaps" v-model="mysubject">
                    <b-form-select-option :value="item.name" v-for="(item,idx)  in subjects"  :key="idx">{{item.name}}</b-form-select-option>
              </b-form-select>
            </b-col>
            <b-col cols="12" sm="6" class="mb-2">
              <b-input-group>
                <b-form-input v-model="search" size="sm" placeholder="Search quiz title"></b-form-input>
                <b-input-group-append>
                  <div class="create-btn">
                    <div class="btn btn-create btn-sm">Search</div>
                  </div>
                </b-input-group-append>
              </b-input-group>
            </b-col>
          </b-row>
          <b-card-text>
            <b-table  v-if="sortedQuiz.length" responsive :fields="fields" :items="sortedQuiz" bordered>
              <template v-slot:cell(Sn)="data">{{data.index+1}}</template>
              <template v-slot:cell(action)="data">
                <span @click="checkPresence(data.item)" class="cpointer">View</span>
              </template>
              <template v-slot:cell(title)="data">
                <div class="main-title">
                  <div class="title">{{JSON.parse(data.item.option).title}}</div>
                  <div class="text-muted">{{JSON.parse(data.item.option).description}}</div>
                </div>
              </template>
              <template v-slot:cell(deadline)="data">
                <span>{{new Date(data.item.end )| moment('DD MMM, hh:mm A')}}</span>
              </template>
              <template v-slot:cell(status)="data">
                <span>{{data.item.end |timeStatus}}</span>
              </template>
            </b-table>
              <b-alert v-else show>No data</b-alert>
          </b-card-text>
        </b-tab>
        <b-tab title="Test">
          <b-row class="justify-content-end my-5">
           <b-col cols="12" sm="3" class="mb-2">
              <b-form-select size="sm" class="toCaps" v-model="mysubject">
                    <b-form-select-option :value="item.name" v-for="(item,idx)  in subjects"  :key="idx">{{item.name}}</b-form-select-option>
              </b-form-select>
            </b-col>
            <b-col cols="12" sm="6" class="mb-2">
              <b-input-group>
                <b-form-input v-model="search" size="sm" placeholder="Search Test title"></b-form-input>
                <b-input-group-append>
                  <div class="create-btn">
                    <div class="btn btn-create btn-sm">Search</div>
                  </div>
                </b-input-group-append>
              </b-input-group>
            </b-col>
          </b-row>
          <b-card-text>
            <b-table v-if="sortedTest.length"  responsive :fields="fields" :items="sortedTest" bordered>
              <template v-slot:cell(Sn)="data">{{data.index+1}}</template>
              <template v-slot:cell(action)="data">
                <span @click="checkPresence(data.item)" class="cpointer">View</span>
              </template>
              <template v-slot:cell(deadline)="data">
                <span>{{new Date(data.item.end )| moment('DD MMM, hh:mm A')}}</span>
              </template>
              <template v-slot:cell(title)="data">
                <div class="main-title">
                  <div class="title">{{JSON.parse(data.item.option).title}}</div>
                  <div class="text-muted">{{JSON.parse(data.item.option).description}}</div>
                </div>
              </template>
              <template v-slot:cell(status)="data">
                <span>{{data.item.end |timeStatus}}</span>
              </template>
            </b-table>
              <b-alert v-else show>No data</b-alert>
          </b-card-text>
        </b-tab>

        <b-tab title="Exam">
          <b-row class="justify-content-end my-5">
           <b-col cols="12" sm="3" class="mb-2">
              <b-form-select class="toCaps" size="sm" v-model="mysubject">
                    <b-form-select-option :value="item.name" v-for="(item,idx)  in subjects"  :key="idx">{{item.name}}</b-form-select-option>
              </b-form-select>
            </b-col>
            <b-col cols="12" sm="6" class="mb-2">
              <b-input-group>
                <b-form-input v-model="search" size="sm" placeholder="Search title"></b-form-input>
                <b-input-group-append>
                  <div class="create-btn">
                    <div class="btn btn-create btn-sm">Search</div>
                  </div>
                </b-input-group-append>
              </b-input-group>
            </b-col>
          </b-row>
          <b-card-text>
            <b-table :fields="fields" :items="sortedExam" bordered v-if="sortedExam.length">
              <template v-slot:cell(Sn)="data">{{data.index+1}}</template>
              <template v-slot:cell(action)="data">
                <span @click="checkPresence(data.item)" class="cpointer">View</span>
              </template>
              <template v-slot:cell(title)="data">
                <div class="main-title">
                  <div class="title">{{JSON.parse(data.item.option).title}}</div>
                  <div class="text-muted">{{JSON.parse(data.item.option).description}}</div>
                </div>
              </template>
              <template v-slot:cell(deadline)="data">
                <span>{{new Date(data.item.end) | moment('DD MMM, hh:mm A')}}</span>
              </template>
              <template v-slot:cell(status)="data">
                <span>{{data.item.end |timeStatus}}</span>
              </template>
            </b-table>
              <b-alert v-else show>No data</b-alert>
          </b-card-text>
        </b-tab>

        <b-tab title="Result">
          <b-row>
            <b-col md="9">
              <b-container>
                <b-row>
                  <b-col md="3" class="mb-2">
                    <b-form-select size="sm" v-model="type" :options="assessmentType"></b-form-select>
                  </b-col>
                  <b-col md="3" class="mb-2">
                    <b-form-select size="sm" class="toCaps" v-model="subject">
                      <b-form-select-option value>Subject</b-form-select-option>
                      <b-form-select-option
                        :value="item.name.toLowerCase()"
                        v-for="(item,idx) in subjects"
                        class="toCaps"
                        :key="idx"
                      >{{item.name}}</b-form-select-option>
                    </b-form-select>
                  </b-col>
                  <b-col md="2" class="mb-2">
                    <b-form-select size="sm"></b-form-select>
                  </b-col>
                    <b-col md="2" class="mb-2">
                      <span @click="refresh">
                        Reset
                        <i class="fa fa-refresh" aria-hidden="true"></i>
                      </span>
                    </b-col>
                </b-row>
              </b-container>
            </b-col>
            <b-col md="3">
              <b-form-input placeholder="Search title" size="sm" v-model="search" class="search rounded-pill"></b-form-input>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <div class="mb-3 toCaps">{{type}}</div>
             <div v-if="sortData.length">
                <b-table
              responsive
                id="table-transition-example"
                sticky-header
                :fields="fields1"
                head-variant="light"
                :items="sortData"
                :tbody-transition-props="transProps"
                primary-key="id"
                bordered
              >
                <template v-slot:cell(score)="data">
                  <div>{{Math.round(data.item.total_score)}}/{{Math.round(data.item.overall)}}</div>
                </template>

                <template v-slot:cell(percentage(%))="data">
                  <div>{{Math.round(((data.item.total_score/data.item.overall)* 100)) || 0}}</div>
                </template>

                <template v-slot:cell(action)="data">
                  <div class="cpointer" @click="preview(data.item)">View Result</div>
                </template>
              </b-table>
             </div>
             <b-alert>No result available</b-alert>
            </b-col>
          </b-row>
        </b-tab>
      </b-tabs>
    </b-card>
    <b-modal id="preview" size="lg" title="Result" hide-footer>
      <Preview :form="form" :options="options" />
    </b-modal>
  </b-container>
</template>


<script>
import Preview from "../preview";
export default {
  props: ["student"],
  data() {
    return {
      search: "",
      tutors: [],
      busy: true,
      items: [],
      item: false,
      quiz: [],
      test: [],
      subject: "",
      mysubject:'',
      form: [],
      options: {},
      assignment: [],
      examination: [],
      type: "",
      subjects: [],
      assessmentType: [
        { value: "", text: "All" },
        { value: "assignment", text: "Assignment" },
        { value: "quiz", text: "Quiz" },
        { value: "test", text: "Test" },
        { value: "exam", text: "Examination" },
      ],
      fields: [
        {
          key: "title",
          sortable: true,
        },
        "subject",
        {
          key: "title",
          sortable: true,
        },
        "deadline",
        "status",
        "action",
      ],
      transProps: {
        // Transition name
        name: "flip-list",
      },
      fields1: [
        { key: "subject", sortable: true },
        { key: "type", sortable: true },
        { key: "title", sortable: true },
        { key: "score", sortable: true },

        "percentage(%)",
        "action",
      ],
      ass_result: [],
    };
  },
  watch: {
    item: "selectAll",
  },
  components: {
    Preview,
  },
  computed: {
    sortedExam() {
     var ans = this.examination.filter((i) => {
        return i.title.toLowerCase().includes(this.search.toLowerCase());
      });
      return ans.filter(item=>item.subject.toLowerCase().includes(this.mysubject.toLowerCase()))
    },
    sortedTest() {
     var ans = this.test.filter((i) => {
        return i.title.toLowerCase().includes(this.search.toLowerCase());
      });
      return ans.filter(item=>item.subject.toLowerCase().includes(this.mysubject.toLowerCase()))
    },
    sortedAss() {
     var ans = this.assignment.filter((i) => {
        return i.title.toLowerCase().includes(this.search.toLowerCase());
      });
      return ans.filter(item=>item.subject.toLowerCase().includes(this.mysubject.toLowerCase()))
    },
    sortedQuiz() {
      var ans = this.quiz.filter((i) => {
        return i.title.toLowerCase().includes(this.search.toLowerCase());
      });
      return ans.filter(item=>item.subject.toLowerCase().includes(this.mysubject.toLowerCase()))
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
    this.getData();
    this.getSub();
    this.getAss();
  },
  methods: {
    preview(val) {
      this.options.title = val.title;
      this.options.level = val.level;
      this.options.subject = val.subject;
      this.options.total_score = val.total_score;
      this.options.overall = val.overall;
      this.form = JSON.parse(val.record);
      this.$bvModal.show("preview");
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
            this.mysubject= res.data[0].name
           
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
    selectAll() {
      if (this.item) {
        this.items = [];
        this.tutors.forEach((it) => {
          this.items.push(it.id);
        });
      } else {
        this.items = [];
      }
    },

    drop(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/tutor/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.student.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getstudents();
            }
          });
      }
    },
    multiDrop() {
      let del = confirm("Are you sure about this?");
      let data = {
        data: this.items,
      };
      if (del) {
        axios
          .post("/api/multi-tutor-drop", data, {
            headers: {
              Authorization: `Bearer ${this.$props.student.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getstudents();
            }
          })
          .catch((err) => {
            console.log("del -> err", err.response.data);
          });
      }
    },
    edit(id) {
      this.$router.push(`/student/tutor/update/${id}`);
    },
    checkPresence(info) {
      let data = {
        subject: info.subject,
        type: info.type,
        title: info.title,
      };

      axios
        .post("/api/check-assessment", data, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            if (res.data.status == "begin") {
              this.view(info.id, info.end);
            } else {
              this.$toasted.error("Assessment already submitted");
            }
          }
        });
    },
    refresh() {
      this.subject = this.search = this.type = "";
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
    view(id, num) {
      let today = Date.parse(new Date());
      let d = Date.parse(num);
      if (d < today) {
        this.$toasted.error("Assessment deadline reached");
       } else {
      this.$router.push(`/student/assessment/view/${id}`);
       }
    },
  },
};
</script>

<style scoped>
.container {
  padding-top: 25px;
  padding-bottom: 70px;
}
nav {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr;
  grid-column-gap: 30px;
}
.input-search {
  display: flex;
  justify-content: flex-end;
}
.hiden {
  opacity: 0;
}
.overall-tab {
  padding-top: 20px;
}
.main-table {
  padding-top: 20px;
}
.student-assessment {
  padding: 40px 20px;
}
.card-header {
  background-color: green;
}
.btn-create {
  background: rgba(19, 166, 153, 0.9) !important;
  color: #fff;
}
.main-title {
  width: 350px;
}
.title {
  color: rgba(19, 166, 153, 0.9);
  font-weight: bold;
}
@media screen and (max-width: 900px) {
  .container,td,th{
    font-size:14px;
  }
  .activity-table {
    overflow: scroll;
  }
  .main-title {
  width: 200px;
  font-size:14px;
}
th{
  font-size:14px;
  text-align:center;
}
}
</style>