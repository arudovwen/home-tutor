<template>
  <div class="body">
    <b-container v-if="!quest">
      <h3 class="toCaps mb-5">{{$route.params.type}}</h3>
      <b-form @submit.prevent="switchQuest">
        <b-form-row>
          <b-col cols="6">
            <b-form-group label="Enter Title ">
              <b-form-input required v-model="option.title"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="6">
            <b-form-group label="Number of questions ">
              <b-form-input required v-model="option.questions" type="number"></b-form-input>
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-form-row>
          <b-col cols="6">
            <b-form-group label="Enter Description ">
              <b-textarea placeholder="Give a description" v-model="option.description"></b-textarea>
            </b-form-group>
          </b-col>
          <b-col cols="6">
            <b-form-group label="Enter Feedback ">
              <b-textarea placeholder="Write a feedback" v-model="option.feedback"></b-textarea>
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-form-group label="Tools">
          <b-form-row>
            <b-form-checkbox v-model="option.tools" value="calculator">Calculator</b-form-checkbox>
            <b-form-checkbox v-model="option.tools" value="notepad">Notepad</b-form-checkbox>
          </b-form-row>
        </b-form-group>
        <b-form-row>
          <b-col md="4">
            <b-form-group label="Select Class">
              <b-form-select v-model="option.myclass">
                <b-form-select-option :value="null" disabled>Select</b-form-select-option>
                <b-form-select-option
                  :value="item.class_name"
                  v-for="(item,index) in allClass"
                  :key="index"
                >{{item.class_name}}</b-form-select-option>
              </b-form-select>
            </b-form-group>
          </b-col>

          <b-col md="4" v-if="tutor.course_level !=='tertiary'">
            <b-form-group label="Select Arm">
              <b-form-select v-model="option.myarm">
                <b-form-select-option :value="null" >All</b-form-select-option>
                <b-form-select-option
                  :value="item"
                  v-for="(item,index) in splitArms(sortedArms)"
                  :key="index"
                >{{item}}</b-form-select-option>
              </b-form-select>
            </b-form-group>
          </b-col>
          <b-col md="4">
            <b-form-group label="Select Subject">
              <b-form-select v-model="option.subject" v-if="sortedSubject.length">
                <b-form-select-option :value="null" disabled>Select</b-form-select-option>
                <b-form-select-option
                class="toCaps"
                  :value="item"
                  v-for="(item,idx) in JSON.parse(sortedSubject[0].classsubject.subjects)"
                  :key="idx"
                >{{item}}</b-form-select-option>
              </b-form-select>
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-form-row>
          <b-col md="6">
            <b-form-group label="Select Module(choose a class and subject first)">
              <b-form-select
                required
                v-model="option.module"
                :disabled="(option.myclass==null && option.subject==null)"
              >
                <b-form-select-option :value="null" disabled>Select</b-form-select-option>
                <b-form-select-option
                  :value="item.name"
                  v-for="(item,idx) in modules"
                  class="toCaps"
                  :key="idx"
                >{{item.name}}</b-form-select-option>
              </b-form-select>
            </b-form-group>
          </b-col>
          <b-col md="6" v-if="tutor.course_level !=='tertiary'">
            <b-form-group label="Select Term">
              <b-form-select v-model="option.session" required>
                <b-form-select-option value disabled>Select term</b-form-select-option>
                <b-form-select-option value="first">First</b-form-select-option>
                <b-form-select-option value="second">Second</b-form-select-option>
                <b-form-select-option value="third">Third</b-form-select-option>
              </b-form-select>
            </b-form-group>
          </b-col>
           <b-col md="6" v-else>
            <b-form-group label="Select Semester">
              <b-form-select v-model="option.session" required>
                <b-form-select-option value disabled>Select term</b-form-select-option>
                <b-form-select-option value="first">First</b-form-select-option>
                <b-form-select-option value="second">Second</b-form-select-option>
               
              </b-form-select>
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-form-row>
          <b-col md="6">
            <b-form-group label="Start Time">
              <datetime format="MM/DD/YYYY" v-model="option.duration.start_time"></datetime>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="End Time">
              <datetime format="MM/DD/YYYY" v-model="option.duration.end_time"></datetime>
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-form-row>
          <b-col md="6">
            <b-form-group label="Grading method">
              <b-form-select v-model="option.grading">
                <b-form-select-option value="automatic">Automatic</b-form-select-option>
                <b-form-select-option value="manual">Manual</b-form-select-option>
              </b-form-select>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Result">
              <b-form-select v-model="option.result" required>
                <b-form-select-option value="automatic">Automatic</b-form-select-option>
                <b-form-select-option value="manual">Manual</b-form-select-option>
              </b-form-select>
            </b-form-group>
          </b-col>
        </b-form-row>
        <div class="right-btn">
          <button class="btn text-center" type="submit">
            <span>NEXT PAGE</span>
            <i class="fa fa-arrow-right" aria-hidden="true"></i>
          </button>
        </div>
      </b-form>
    </b-container>

    <assess v-if="quest" @handleDuration="handleDuration" :option="option"></assess>
  </div>
</template>

<script>
import datetime from "vuejs-datetimepicker";
import assess from "./update";
export default {
  props:['tutor'],
  components: { datetime, assess },
  data() {
    return {
      id:null,
      quest: false,
      option: {
        type: "",
        session: "",
        description: "",
        questions: 0,
        feedback: "",
        title: "",
        myclass: null,
        myarm:null,
        module: null,
        subject: null,
        duration: {
          start_time: new Date().toLocaleDateString(),
          end_time: new Date().toLocaleDateString()
        },
        grading: "automatic",
        result: "automatic",
        tools: [],
        timer: null,
      },
      form:[],
      modules: [],
      subjects: [],
      allClass: [],
    };
  },
    computed: {
     sortedSubject() {
    if(this.option){
        var subjects = this.allClass.filter((item) =>
        item.class_name
          .toLowerCase()
          .includes(this.option.myclass.toLowerCase())
      );

      return subjects;
    }
    },
     sortedArms() {
if(this.option){
    var subjects = this.allClass.filter((item) =>
        item.class_name
          .toLowerCase()
          .includes(this.option.myclass.toLowerCase())
      );

      return subjects;
    }
     }
},
  mounted() {
    this.option.type = this.$route.params.type;
    this.getSubjects();
    this.getCLasses();
    this.getInfo()
  },
  watch: {
    "option.subject": "getModules",
    "option.myclass": "getModules",
  },
  methods: {
    handleDuration(val) {
      this.option.timer = val;
    },
     getInfo() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      axios
        .get(
          `/api/get-assessment/${this.$route.params.id}`,
          {
            headers: {
              Authorization: `Bearer ${tutor.access_token}`,
            },
          }
        )
        .then((res) => {
          if (res.status == 200) {
            this.form = JSON.parse(res.data.assessment);
            this.option = JSON.parse(res.data.option);
          }
        });
    },
       splitArms(arr){
      var myarr = []
      arr.forEach((item) => {
             
              if (item.sub_class !== "") {
                item.sub_class.split(",").forEach((i) => {
                myarr.push(i);
                });
              }
            });
            return myarr;
    },
    getModules() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      axios
        .get(
          `/api/tutor-module/${this.option.myclass}/${this.option.subject}`,
          {
            headers: {
              Authorization: `Bearer ${tutor.access_token}`,
            },
          }
        )
        .then((res) => {
          if (res.status == 200) {
            this.modules = res.data;
          }
        });
    },
    switchQuest() {
      this.quest = !this.quest;
    },
    getCLasses() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      axios
        .get("/api/all-classes", {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.allClass= res.data
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
    getSubjects() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      axios
        .get("/api/tutor-all-subjects", {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.subjects = res.data;
          }
        })
        .catch();
    },
    cancel(id) {
      this.assessment.splice(id, 1);
      if (this.opened.includes(id)) {
        let ele = this.opened.indexOf(id);

        this.opened.splice(ele);
      }
    },
    hideNow(id) {
      this.opened.push(id);
    },
    cancelOption(id) {
      this.assessment[id].values.pop();
    },
    cancelAnswer(id) {
      this.assessment[id].answers.pop();
    },
    addNew(id) {
      this.opened.push(id);

      this.assessment.push({
        note: "",
        question: "",
        student_answer: "",
        student_answers: [],
        answer: "",
        answers: [
          {
            answer: "",
          },
        ],
        type: null,
        answer_type: "single",
        score: null,
        values: [
          {
            value: "",
          },
          {
            value: "",
          },
        ],
      });
    },
    addAns(id) {
      this.assessment[id].answers.push({
        answer: "",
      });
    },
    addOption(id) {
      this.assessment[id].values.push({
        value: "",
      });
    },
    show(id) {
      if (this.opened.includes(id)) {
        let ele = this.opened.indexOf(id);

        this.opened.splice(ele);
      }
    },
    saveAssessment() {
      let ask = confirm("Are you done?");
      if (ask) {
        let tutor = JSON.parse(localStorage.getItem("typeTutor"));
        let data = {
           option:this.option,
          subject: this.subject,
          session: this.session,
          title: this.title,
          type: this.type,
          level: this.myclass,
          duration: JSON.stringify(this.duration),
          assessment: this.assessment,
          total_score: this.total_score,
        };
        axios
          .post("/api/assessment", data, {
            headers: {
              Authorization: `Bearer ${tutor.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 201) {
              this.recordTimeline("new assessment");
              this.$toasted.success("Created successfully");
              this.$router.go(-1);
            }
          })
          .catch();
      }
    },
  },
};
</script>

<style scoped>
.main-box {
  padding: 20px;
  background: lightblue;
  position: relative;
}
.top-box {
  margin: 0 auto;
  background: white;
  height: auto;
}
.bottom-box {
  margin: 0 auto;
  background: white;
}
.hide {
  height: 120px;
  overflow: hidden;
}
.btn-sm {
  padding: 0.1rem 0.4rem;
  font-size: 0.4rem;
  line-height: 1.5;
}
.custom-select {
  display: inline-block !important;
  width: 100%;
  height: calc(1.5em + 0.75rem + 2px);
  padding: 0.375rem 1.75rem 0.375rem 0.75rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #495057;
  vertical-align: middle;
  /* background: #fff; */
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
  /* -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none; */
  background: #fff url(/images/down-chevron.png) no-repeat right 0.75rem
    center/8px 10px;
}
.right-btn {
  display: flex;
  justify-content: flex-end;
  margin-right: 25px;
  margin-top: 25px;
}
.btn {
  background: transparent;
  border: 1px solid #0a4065;
  color: #0a4065;
  font-weight: 500;
}
.btn:hover {
  background: #0a4065;
  color: white;
}
a {
  text-decoration: none !important;
}
a:hover {
  text-decoration: none !important;
}
</style>