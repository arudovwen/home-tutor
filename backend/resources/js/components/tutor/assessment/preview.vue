<template>
  <div>
    <b-container v-if="form">
      <b-form>
        <legend class="text-center mb-5">{{options.title}}</legend>
        <b-row>
          <b-col sm="6">
            <strong class="toCaps">{{options.session}} Term</strong>
          </b-col>
          <b-col sm="6">
            <strong class="toCaps">Level : {{options.myclass}}</strong>
          </b-col>
          <b-col sm="6">
            <strong class="toCaps">Subject : {{options.subject}}</strong>
          </b-col>
          <b-col sm="6">
            <strong class="toCaps">
              Start : {{options.duration.start_time}}
              <br />
              End : {{options.duration.end_time}}
            </strong>
          </b-col>
        </b-row>
        <hr>
        <section v-if=" num < form.length">
          <b-form-row>
            <b-col cols="12">
              <h5>{{form[num].title}}</h5>
              <p v-html="form[num].description"></p>
            </b-col>
          </b-form-row>
          <b-form-row v-for="(question,idx) in form[num].question" :key="idx">
            <b-col cols="12">
              <b-form-group v-if="question.answer_format=='text box'">
                <label for>{{question.title}}</label>
                <small class="form-text">{{question.guide}}</small>
                <b-form-input
                  type="text"
                  v-model="question.answer"
                  :placeholder="question.placeholder"
                  :max="question.limit"
                ></b-form-input>
              </b-form-group>
              <!-- <b-form-group >
                            <label for=""></label>
                            <b-form-input type="tel" placeholder=""></b-form-input>
              </b-form-group>-->

              <b-form-group v-if="question.answer_format=='email'">
                <label for>{{question.title}}</label>
                <small class="form-text">{{question.guide}}</small>
                <b-form-input
                  type="email"
                  v-model="question.answer"
                  :placeholder="question.placeholder"
                ></b-form-input>
              </b-form-group>
              <b-form-group v-if="question.answer_format=='number'">
                <label for>{{question.title}}</label>
                <small class="form-text">{{question.guide}}</small>
                <b-form-input
                  type="number"
                  v-model="question.answer"
                  :placeholder="question.placeholder"
                  :max="question.limit"
                ></b-form-input>
              </b-form-group>

              <b-form-group v-if="question.answer_format=='long text'">
                <label for>{{question.title}}</label>
                <b-form-textarea :placeholder="question.placeholder" v-model="question.answer"></b-form-textarea>
              </b-form-group>

              <b-form-group v-if="question.answer_format=='multi choice'">
                <label for>{{question.title}}</label>
                <small class="form-text">{{question.guide}}</small>
                <b-form-checkbox
                  v-model="question.answers"
                  v-for="(opt,id) in question.options"
                  :key="id"
                  :value="opt.name"
                >{{opt.name}}</b-form-checkbox>
              </b-form-group>

              <b-form-group v-if="question.answer_format=='single choice'">
                <label for>{{question.title}}</label>
                <small class="form-text">{{question.guide}}</small>
                <b-form-radio
                  v-model="question.answer"
                  v-for="(opt,id) in question.options"
                  :key="id"
                  :value="opt.name"
                >{{opt.name}}</b-form-radio>
              </b-form-group>

              <b-form-group v-if="question.answer_format=='date'">
                <label for>{{question.title}}</label>
                <small class="form-text">{{question.guide}}</small>
                <br />
                <b-form-datepicker v-model="question.answer"></b-form-datepicker>
              </b-form-group>

              <b-form-group v-if="question.answer_format=='time'">
                <label for>{{question.title}}</label>
                <small class="form-text">{{question.guide}}</small>
                <br />
                <b-form-timepicker v-model="question.answer"></b-form-timepicker>
              </b-form-group>

              <b-form-group v-if="question.answer_format=='multi text'">
                <div v-for="(item,i) in question.answers" :key="i">
                  <b-form-input v-model="item.answer"></b-form-input>
                </div>
              </b-form-group>
            </b-col>
            <b-col>
              <div v-if="question.tools.length">
                <b-form-row v-if="question.tools.includes('table')">
                  <b-col cols="12">
                    <b-table :fields="question.fields" :items="question.items"></b-table>
                  </b-col>
                </b-form-row>

                <b-form-row
                  v-if="question.tools.includes('docs') || question.tools.includes('media')"
                >
                  <b-col cols="12">
                    <b-form-row>
                      <b-col
                        cols="3"
                        class="border p-2"
                        v-for="(doc,idx) in question.documents"
                        :key="idx"
                      >
                        <div>
                          <b-form-input v-model="doc.name" type="text" placeholder="File name"></b-form-input>
                          <Upload />
                        </div>
                      </b-col>
                    </b-form-row>
                  </b-col>
                </b-form-row>
              </div>
            </b-col>
          </b-form-row>
        </section>

        <b-row class="justify-content-between my-3">
          <b-button @click="prev">Previous</b-button>
          <b-button v-if="num <form.length +1" @click="next">Next</b-button>
        </b-row>
      </b-form>
    </b-container>
  </div>
</template>

<script>
import Upload from "../../miniupload";

export default {
  props: ["form", "options"],
  data() {
    return {
      lgas: [],
      num: 0,
      show: false,
    };
  },
  components: {
    Upload,
  },
  mounted() {},
  methods: {
    handleShow() {
      this.show = !this.show;
    },
    next() {
      this.num++;
    },
    prev() {
      if (this.num !== 0) {
        this.num--;
      }
    },
  },
};
</script>

<style scoped>
</style>