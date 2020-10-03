<template>
  <div class="W-100 form" v-if="option">
    <div class="calc text-right" >
      <div class="mb-2 d-flex justify-content-end">
        <div class="mr-2">
          <b-button  @click="toggleCalc" size="sm"  v-if="option.tools.includes('calculator')"> <b-icon icon="calculator"></b-icon></b-button>
        </div>
      
       <div>
          <b-button @click="toggleNote" size="sm"  v-if="option.tools.includes('notepad')">  <b-icon icon="clipboard"></b-icon></b-button>
       </div>
      </div>
      <div>
        <Calculator v-if="showCalc" />
      </div>
       <div v-if="showNote">
        <b-form class="border shadow-lg">
         <b-form-group class=" text-center  rounded">
            <b-form-textarea  cols="30" rows="10" v-model="note" size="sm" placeholder="Type note here..."></b-form-textarea>
            <b-button size="sm" @click="save" class="my-3">save</b-button>
         </b-form-group>
        </b-form>
      </div>
    </div>
    <b-row class="my-1 justify-content-between">
    
      <b-col cols="12" sm="6">
       
        <vue-countdown-timer
        v-if="details.type=='exam'"
        class="timer"
          @start_callback="startCallBack()"
          @end_callback="endCallBack()"
          :start-time="new Date()"
          :end-time="addMin(option.timer)"
          :interval="1000"
          :start-label="'Until start:'"
          :end-label="'Timer'"
          label-position="begin"
          :end-text="'Event ended!'"
          :day-txt="''"
          :hour-txt="'hrs'"
          :minutes-txt="'mins'"
          :seconds-txt="'secs'"
        ></vue-countdown-timer>
      </b-col>
    </b-row>
    <b-form @submit.prevent="submit" class="border p-5">
      <section v-if="num < template.length">
        <div class="total">
          Questions : {{details.questions}}
          <br />Total :
          <strong>{{total}}</strong>
          <br />
          <!-- <span v-if="showScores">
            Your score :
            <strong>{{total_scores}}</strong>
          </span>-->
        </div>
        <b-form-row>
          <b-col cols="12">
            <h6>{{template[num].title}}</h6>
            <p v-html="template[num].description"></p>
          </b-col>
        </b-form-row>
        <b-form-row>
          <ol class="w-100">
            <li v-for="(question,idx) in template[num].question" :key="idx">
              <b-col cols="12">
                <b-form-group v-if="question.answer_format=='text box'">
                  <label for>
                    {{question.title}} <b-icon icon="info-circle-fill" v-if="question.guide" :id="idx+'guide'+num"></b-icon> (
                    <span class="scores">{{question.score}} marks</span>)
                  </label>
                  <!-- <small class="form-text" v-if="question.guide">{{question.guide}}</small> -->

                  <b-form-input
                    type="text"
                    :disabled="showScores"
                    v-model="question.answer"
                    :placeholder="question.placeholder"
                    :max="question.limit"
                  ></b-form-input>
               
                </b-form-group>

                <b-form-group v-if="question.answer_format=='email'">
                  <label for>
                    {{question.title}} <b-icon icon="info-circle-fill" v-if="question.guide" :id="idx+'guide'+num"></b-icon> (
                    <span class="scores">{{question.score}} marks</span>)
                  </label>
                  <!-- <small class="form-text" v-if="question.guide">{{question.guide}}</small> -->
                  <b-form-input
                    :disabled="showScores"
                    type="email"
                    v-model="question.answer"
                    placeholder="example@email.com"
                  ></b-form-input>
                </b-form-group>
                <b-form-group v-if="question.answer_format=='number'">
                  <label for>
                    {{question.title}} <b-icon icon="info-circle-fill" :id="idx+'guide'+num"></b-icon> (
                    <span class="scores">{{question.score}} marks</span>)
                  </label>
                  <!-- <small class="form-text" v-if="question.guide">{{question.guide}}</small> -->
                  <b-form-input
                    type="number"
                    :disabled="showScores"
                    @change="calcAnswer(idx,question)"
                    v-model="question.answer"
                    :placeholder="question.placeholder"
                    :max="question.limit"
                  ></b-form-input>
                </b-form-group>

                <b-form-group v-if="question.answer_format=='long text'">
                  <label for>
                    {{question.title}}  <b-icon icon="info-circle-fill" v-if="question.guide" :id="idx+'guide'+num"></b-icon> (
                    <span class="scores">{{question.score}} marks</span>)
                  </label>
                  <!-- <small class="form-text" v-if="question.guide">{{question.guide}}</small> -->
                  <b-form-textarea
                    :disabled="showScores"
                    :placeholder="question.placeholder"
                    v-model="question.answer"
                  ></b-form-textarea>
                </b-form-group>

                <b-form-group v-if="question.answer_format=='multi choice'">
                  <label for>
                    {{question.title}} <b-icon icon="info-circle-fill" v-if="question.guide" :id="idx+'guide'+num"></b-icon> (
                    <span class="scores">{{question.score}} marks</span>)
                  </label>
                  <!-- <small class="form-text" v-if="question.guide">{{question.guide}}</small> -->
                  <b-row >
                    <b-col cols="6"   v-for="(opt,id) in question.options"
                      :key="id">
                      <b-form-checkbox
                      :disabled="showScores"
                      v-model="question.answers"
                      @change="calcAnswer(idx,question,$event)"
                    
                      :value="opt.name"
                    >{{opt.name}}</b-form-checkbox>
                    </b-col>
                  </b-row>
                </b-form-group>

                <b-form-group v-if="question.answer_format=='single choice'">
                  <label for>
                    {{question.title}} <b-icon icon="info-circle-fill" v-if="question.guide" :id="idx+'guide'+num"></b-icon> (
                    <span class="scores">{{question.score}} marks</span>)
                  </label>
                  <!-- <small class="form-text" v-if="question.guide">{{question.guide}}</small> -->
                  <b-row >
                   <b-col cols="6"  v-for="(opt,id) in question.options"
                      :key="id">
                      <b-form-radio
                      :disabled="showScores"
                      @change="calcAnswer(idx,question,$event)"
                      v-model="question.answer"
                     
                      :value="opt.name"
                    >{{opt.name}}</b-form-radio>
                   </b-col>
                  </b-row>
                </b-form-group>

                <b-form-group v-if="question.answer_format=='date'">
                  <label for>
                    {{question.title}} <b-icon icon="info-circle-fill" v-if="question.guide" :id="idx+'guide'+num"></b-icon> (
                    <span class="scores">{{question.score}} marks</span>)
                  </label>
                  <!-- <small class="form-text" v-if="question.guide">{{question.guide}}</small> -->
                  <br />
                  <b-form-datepicker
                    :disabled="showScores"
                    @change="calcAnswer(idx,question,$event)"
                    v-model="question.answer"
                  ></b-form-datepicker>
                </b-form-group>

                <b-form-group v-if="question.answer_format=='time'">
                  <label for>
                    {{question.title}} <b-icon icon="info-circle-fill" v-if="question.guide" :id="idx+'guide'+num"></b-icon> (
                    <span class="scores">{{question.score}} marks)</span>
                  </label>
                  <!-- <small class="form-text" v-if="question.guide">{{question.guide}}</small> -->
                  <br />
                  <b-form-timepicker
                    :disabled="showScores"
                    @change="calcAnswer(idx,question,$event)"
                    v-model="question.answer"
                  ></b-form-timepicker>
                </b-form-group>

                <b-form-group v-if="question.answer_format=='multi text'">
                  <label for>
                    {{question.title}}  <b-icon icon="info-circle-fill" v-if="question.guide" :id="idx+'guide'+num"></b-icon> (
                    <span class="scores">{{question.score}} marks</span>)
                  </label>
                  <small class="form-text mb-2" v-if="question.guide">{{question.guide}}</small>
                  <div v-for="(item,i) in question.answers" :key="i">
                    <b-form-input
                      :disabled="showScores"
                      @change="calcAnswer(idx,question,$event)"
                      v-model="item.answer"
                    ></b-form-input>
                  </div>

                  <div>
                    <b-button size="sm" @click="addMore(idx)" class="mr-3">
                      <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </b-button>
                    <b-button size="sm" @click="removeMore(idx)" v-if="question.answers.length> 1">
                      <i class="fa fa-minus-circle" aria-hidden="true"></i>
                    </b-button>
                  </div>
                </b-form-group>
              </b-col>
              <b-col class="text-left">
                <b-img :src="question.media" v-if="question.media" width="80px"></b-img>
              </b-col>
                  <b-tooltip :target="idx+'guide'+num" triggers="hover">
                {{question.guide}}
                </b-tooltip>
            </li>
          </ol>
        </b-form-row>
      </section>

      <b-row class="justify-content-between px-3 my-3">
        <b-button size="sm" @click="prev">Previous</b-button>
        <b-button size="sm" v-if="num < template.length-1" @click="next">Next</b-button>

        <div v-if="num == template.length-1">
          <b-button size="sm" type="submit">Submit</b-button>
        </div>
      </b-row>
    </b-form>

    <b-modal id="completed" size="lg" hide-footer>
      <div class="text-center">
        You have completed this Assessment
        <!-- <br />your score is
        <br />
        <span class="display-4">{{total_scores}}</span>-->
      </div>
      <div class="text-center">{{details.feedback}}</div>
      <div class="text-center">
         <b-button size="sm" type="submit">Submit</b-button>
      </div>
    </b-modal>

      <b-modal id="ended" size="lg" hide-footer>
      <div class="text-center">
       <b-icon icon="info-circlefill"></b-icon> Your time is up
       
      </div>
     
      <div class="text-center">
        <b-button size="sm" variant="outline-darkgreen" @click="closeForm">Close</b-button>
      </div>
    </b-modal>
  </div>
</template>

<script>
import Upload from "../../miniupload";
import Calculator from "../../Calculator";
export default {
  props: ["template", "total", "details", "option"],
  data() {
    return {
     
      scores: [],
      template_score: 0,
      temp_score: 0,
      num: 0,
      note:'',
      show: false,
      total_scores: 0,
      submitCount: 0,
      showScores: false,
      newAns: [],
      showCalc: false,
      showNote: false,
    };
  },
  components: {
    Upload,
    Calculator,
  },
  mounted() {},
  methods: {
     save() {
        let student = JSON.parse(localStorage.getItem("typeStudent"));
      let data = {
        title: this.template[this.num].title+' note',
        description: this.note,
      };
      axios
        .post("/api/note", data, {
          headers: {
            Authorization: `Bearer ${student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
           
         
            this.$toasted.success("Note saved");
          this.showNote = false
          }
        })
        .catch();
    },
    addMin(time) {
      return new Date(new Date().getTime()+time*60000);
    },
    toggleCalc() {
      this.showCalc = !this.showCalc;
    },
    toggleNote() {
      this.showNote = !this.showNote;
    },
    startCallBack() {},
    endCallBack() {
      this.$bvModal.show("ended");
      
      this.$router.push('/student/assessment')
    },
    submit() {
      if (!this.showScores) {
        // this.total_scores += this.sectionAnswer();

        this.showScores = true;

        let student = JSON.parse(localStorage.getItem("typeStudent"));
        let data = {
          subject: this.$props.details.subject,
          type: this.$props.details.type,
          title: this.$props.option.title,
          tutor_id: this.$props.details.tutor_id,
          total_score: this.total_scores,
          record: this.$props.template,
          overall: this.$props.total,
        };
        axios
          .post("/api/student-assessment-result", data, {
            headers: { Authorization: `Bearer ${student.access_token}` },
          })
          .then((res) => {
            if (res.status == 201) {
              this.$toastd.info('Submitted')
              this.$router.go(-1)
            }
          });
      }
      this.$bvModal.show("completed");
    },

    closeForm() {
      this.$router.go(-1);
    },

    getUploadDetails(index, res, id) {
      this.template[this.num].question[index].documents[index].file =
        res.secure_url;
    },
    next() {
      if (!this.showScores) {
        this.total_scores += this.sectionAnswer();
      }

      this.scores = [];
      this.num++;
    },
    prev() {
      if (this.num !== 0) {
        this.num--;
      }
    },

    removeMore(id) {
      this.template[this.num].question[id].answers.pop();
    },
    addMore(id) {
      this.template[this.num].question[id].answers.push({
        answer: "",
      });
    },
    addDoc(id) {
      this.template[this.num].question[id].documents.push({
        name: "",
        file: "",
      });
    },
    removeDoc(id) {
      this.template[this.num].question[id].documents.pop();
    },
    handleObj() {},
    addRow(id) {
      //  Object.assign(this.obj,  this.template[this.num].question[id].items[0])
      let obj = {};
      this.template[this.num].question[id].fields.forEach((item) => {
        obj[item] = "";
      });
      this.template[this.num].question[id].items.push(obj);
    },
    removeRow(id) {
      this.template[this.num].question[id].items.pop();
    },
    calcAnswer(id, answer, e) {
      var score = 0;
      var result = {};
      this.template[this.num].question[id].student_score = 0;
      if (this.scores.length > 0) {
        this.scores.forEach((item, index) => {
          if (item["id"] == id) {
            this.scores.splice(index, 1);
          } else {
            this.temp_score = 0;
          }
        });
      }
      if (answer.answer_format !== "multi choice") {
        if (e.toLowerCase() == answer.real_answer.toLowerCase()) {
          score = answer.score;
          this.template[this.num].question[id].student_score = answer.score;
        }
      } else {
        this.newAns = [];
        let p_score = Number(answer.score / answer.real_answers.length);

        answer.real_answers.forEach((item) => {
          this.newAns.push(item.answer);
        });
        if (this.newAns.includes(e)) {
          this.temp_score += p_score;
        }
        score = this.temp_score;
        this.template[this.num].question[id].student_score = this.temp_score;
      }
      this.scores.push({
        id: id,
        score: score,
      });
      this.sectionAnswer();
    },
    sectionAnswer() {
      var score = [];
      this.scores.forEach((item) => {
        score.push(item.score);
      });

      return score.reduce((a, b) => {
        return a + b;
      }, 0);
    },
  },
};
</script>

<style scoped>
.container {
  min-height: 70vh;
  padding: 50px 0;
}
.form {
  position: relative;
}
.calc {
  position: absolute;
  top: -7%;
  right: 0px;
  z-index: 20;
}
section {
  position: relative;
}
.total {
  position: absolute;
  right: 0;
  top: -30px;
}
.upload {
  background: #f7f8fa;
}
label {
  font-weight: bold;
}
.border {
  border-radius: 15px;
}
li {
  margin-left: 1rem;
  margin-bottom: 50px;
}
.form-text {
  margin-bottom: 0.25rem;
  margin-top: 0;
  color: blue;
}
.scores {
  font-size: 11px;
}
.answer-box {
  width: 60%;
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-column-gap: 20px;
  grid-row-gap: 15px;
}
@media (max-width: 600px) {
  .container {
    padding: 30px 10px;
  }
  form {
    padding: 40px 10px !important;
    font-size: 14px;;
  }
  .total{
    font-size: 12px;
  }
 
  .calc{
    position: absolute;
    top: -5%;
    right: 0px;
    z-index: 20;
}
.btn-sm{
   font-size: 12px;
}
.timer,.duration{
   font-size: 12px;
}
}
</style>