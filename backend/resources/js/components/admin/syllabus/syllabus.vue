<template>
  <div class="body">
    <b-modal id="preview" size="lg" hide-footer>
      <Preview
        :syllabus="syllabus"
        @submit="submit"
        @togglePreview="togglePreview"
        @updateComment="updateComment"
        :afterSubmit="afterSubmit"
        @cancelToggle="cancelToggle"
      />
    </b-modal>
    <!-- form starts here  -->
    <form @submit.prevent="togglePreview">
      <legend class="text-center">New Syllabus</legend>

      <div class="border p-3 my-4">
        <h5>Class Information</h5>
       
       

         <b-row>
           <b-col cols="12" sm="3">
          <b-form-group label="Category">
            <b-form-select v-model="syllabus.level"  class="toCaps">
              <b-form-select-option  value='select'>Select</b-form-select-option>
              <b-form-select-option
               
                :value="level"
                v-for="(level,idx) in JSON.parse(admin.level)"
                :key="idx"
              >{{level}}</b-form-select-option>
            </b-form-select>
          </b-form-group>
        </b-col>
        <b-col>
            <div class="form-group">
          <label for>Grade Level</label>
          <br />
          <select required class="custom-select w-50" v-model="syllabus.grade_level">
            <option selected disabled value>Select one</option>
            <option
              :value="item.class_name.toLowerCase().trim()"
              v-for="(item,idx) in sortedClass"
              class="toCaps"
              :key="idx"
            >{{item.class_name.trim()}}</option>
          </select>
        </div>
        </b-col>
        </b-row>
       <div class="form-group">
          <label for >{{syllabus.level=='tertiary'?'Semester':'Term'}}</label>
          
          <input
            type="text"
            required
            class="form-control w-50"
            v-model="syllabus.term"
            aria-describedby="helpId"
            placeholder
          />
        </div>
      </div>

      <div class="border p-3 my-4">
        <h5>Subject Information</h5>

        <b-form-group label="Subject">
          <label for></label>
          <b-form-select  required class="custom-select w-50 toCaps" v-model="syllabus.subject" v-if="sortedSubject.length">
            <b-form-select-option selected disabled value>Select one</b-form-select-option>
            <b-form-select-option
              :value="item.toLowerCase().trim()"
              v-for="(item,idx) in JSON.parse(sortedSubject[0].classsubject.subjects)"
              class="toCaps"
              :key="idx"
            >{{item}}</b-form-select-option>
          </b-form-select>
        </b-form-group>

        <div class="form-group">
          <label for>Description</label>
          <small
            class="form-text text-muted"
          >Provide a brief description of the subject, for the specific student profile</small>
          <textarea required class="form-control" rows="3" v-model="syllabus.description"></textarea>
        </div>
        <div class="form-group">
          <label for>Cover Image</label>
          <br>
         <label for="cover">
            <b-avatar :src="syllabus.cover" rounded size="5rem" icon="image-fill"></b-avatar>
          </label>
            <Upload :label="label" :index="label" @getUploadDetails="getUploadDetails" />
        </div>

        <div class="form-group">
          <label for>Frequency</label>
          <input
            type="text"
            required
            v-model="syllabus.frequency"
            class="form-control w-50"
            aria-describedby="helpId"
            placeholder="e.g Once a week "
          />
        </div>
      </div>

      <div class="border p-3 my-4">
        <h5>Overarching Learner Outcome</h5>

        <div class="form-group">
          <small
            id="helpId"
            class="form-text text-muted"
          >What do you intend for students to learn at the end of the session?</small>
          <span v-for="(item,idx) in syllabus.learner_outcome" :key="idx" class="mb-2 d-flex">
            <span class="mr-3">{{idx+1}}.</span>
            <input
              type="text"
              required
              class="form-control mb-2"
              aria-describedby="helpId"
              v-model="item.name"
              placeholder
            />
          </span>

          <div class="d-flex justify-content-start">
            <b-button type="button" class="mr-3" @click="addNew(1)">Add</b-button>
            <b-button
              type="button"
              class
              @click="remove(1)"
              v-if="syllabus.learner_outcome.length > 1"
            >Remove</b-button>
          </div>
        </div>
      </div>
      <div class="border p-3 my-4">
        <h5>Target skills to be learned</h5>
        <small
          id="helpId"
          class="form-text text-muted"
        >What skills do you expect your students to pick up by the end of the session</small>

        <span v-for="(item,idx) in syllabus.target_skills" :key="idx" class="mb-2 d-flex">
          <span class="mr-3">{{idx+1}}.</span>
          <input
            type="text"
            required
            class="form-control w-50 mb-2"
            aria-describedby="helpId"
            v-model="item.name"
            placeholder
          />
        </span>

        <div class="d-flex">
          <b-button type="button" class="mr-3" @click="addNew(2)">Add</b-button>
          <b-button
            type="button"
            class
            @click="remove(2)"
            v-if="syllabus.target_skills.length > 1"
          >Remove</b-button>
        </div>
      </div>
      <div class="border p-3 my-4">
        <h5>Required Modules</h5>
        <small
          id="helpId"
          class="form-text text-muted"
        >What modules/subjects are your students required to cover under this subject</small>
        <span v-for="(item,idx) in syllabus.modules" :key="idx" class="mb-2 d-flex">
          <span class="mr-3">{{idx+1}}.</span>
          <b-form-row>
           <b-col cols="6">
               <input
            type="text"
            required
            class="form-control mb-2"
            aria-describedby="helpId"
            v-model="item.name"
            placeholder='Name'
          />
           </b-col>
        <b-col cols="12">
            <b-form-textarea placeholder="Description" v-model="item.description" max="200"></b-form-textarea>
        </b-col>
          </b-form-row>
        </span>

        <div class="d-flex">
          <b-button type="button" class="mr-3" @click="addNew(3)">Add</b-button>
          <b-button type="button" class @click="remove(3)" v-if="syllabus.modules.length > 1">Remove</b-button>
        </div>
      </div>

      <div class="border p-3 my-4">
        <h5>Requirements</h5>
        <small id="helpId" class="form-text text-muted">Subject requiremnts</small>

        <span v-for="(item,idx) in syllabus.requirements" :key="idx" class="mb-2 d-flex">
          <span class="mr-3">{{idx+1}}.</span>
          <input
            type="text"
            required
            class="form-control w-50 mb-2"
            aria-describedby="helpId"
            v-model="item.name"
            placeholder
          />
        </span>

        <div class="d-flex">
          <b-button type="button" class="mr-3" @click="addNew(7)">Add</b-button>
          <b-button
            type="button"
            class
            @click="remove(7)"
            v-if="syllabus.requirements.length > 1"
          >Remove</b-button>
        </div>
      </div>
      <div class="border p-3 my-4">
        <h5>Delivery Methods</h5>
        <small
          id="helpId"
          class="form-text text-muted"
        >What delivery methods are the teachers expected to employ in teaching the students?</small>

        <span v-for="(item,idx) in syllabus.delivery_methods" :key="idx" class="mb-2 d-flex">
          <span class="mr-3">{{idx+1}}.</span>
          <input
            type="text"
            required
            class="form-control w-50 mb-2"
            aria-describedby="helpId"
            v-model="item.name"
            placeholder
          />
        </span>
        <div class="d-flex">
          <b-button type="button" class="mr-3" @click="addNew(4)">Add</b-button>
          <b-button
            type="button"
            class
            @click="remove(4)"
            v-if="syllabus.delivery_methods.length > 1"
          >Remove</b-button>
        </div>
      </div>
      <div class="border p-3 my-4">
        <h5>Assessment</h5>
        <small id="helpId" class="form-text text-muted">How will the students be assessed?</small>

        <span v-for="(item,idx) in syllabus.assessments" :key="idx" class="mb-2 d-flex">
          <span class="mr-3">{{idx+1}}.</span>
          <input
            type="text"
            required
            class="form-control w-50 mb-2"
            aria-describedby="helpId"
            v-model="item.name"
            placeholder
          />
        </span>

        <div class="d-flex">
          <b-button type="button" class="mr-3" @click="addNew(5)">Add</b-button>
          <b-button
            type="button"
            class
            @click="remove(5)"
            v-if="syllabus.assessments.length > 1"
          >Remove</b-button>
        </div>
      </div>
      <div>
        <h5>FAQs (Optional)</h5>

        <div v-for="(item,idx) in syllabus.faqs" :key="idx" class="mb-3">
          <span class="d-flex mb-2">
            <label for="Question" class="side-label">Question</label>
            <input
              v-model="item.question"
              type="text"
              class="form-control w-75 mb-2"
              aria-describedby="helpId"
              placeholder="Question"
            />
          </span>
          <span class="d-flex">
            <label for class="side-label">Answer</label>
            <input
              v-model="item.answer"
              type="text"
              class="form-control w-75"
              aria-describedby="helpId"
              placeholder="Answer"
            />
          </span>
          <hr v-if="syllabus.faqs.length > 1" />
        </div>
        <div class="d-flex">
          <b-button type="button" class="mr-3" @click="addNew(6)">Add</b-button>
          <b-button type="button" class @click="remove(6)" v-if="syllabus.faqs.length > 1">Remove</b-button>
        </div>
      </div>
      <div class="border p-3 my-4">
        <h5>Course Availability</h5>

        <div class="form-group">
          <select required class="custom-select w-50" v-model="syllabus.availability">
            <option selected value disabled>Select one</option>
            <option value="online">Online</option>
            <option value="offline">Offline</option>
            <option value="both">Both</option>
          </select>
        </div>
      </div>

      <div class="my-5">
        <b-button type="submit" class="btn btn-primary">Preview</b-button>
      </div>
    </form>
    <!-- form ends here  -->
  </div>
</template>

<script>
import Upload from "../../miniupload";
import Preview from "./preview";
export default {
  props: ["admin"],
  data() {
    return {
      subjects: [],
      afterSubmit: false,
      termType: false,
      showPreview: false,
       label: "cover",
      cover: "cover",
      allclasses: [],
      syllabus: {
        level:'select',
        term: "",
        cover: "",
        grade_level: "",
       
        subject: "",
        frequency: "",
        learner_outcome: [{ name: "" }],
        requirements: [{ name: "" }],
        target_skills: [{ name: "" }],
        modules: [{ name: "",description:'' }],
        delivery_methods: [{ name: "" }],
        assessments: [{ name: "" }],
        faqs: [
          {
            question: "",
            answer: "",
          },
        ],
        availability: "",
        comment: "",
      },
    };
  },
  components: {
    Preview,
    Upload,
  },
  computed: {
     sortedClass() {
      var subjects = this.allclasses.filter((item) =>
        item.level.toLowerCase().includes(this.syllabus.level.toLowerCase())
      );

      return subjects;
    },
     sortedSubject() {
      var subjects = this.sortedClass.filter((item) =>
        item.class_name
          .toLowerCase()
          .includes(this.syllabus.grade_level.toLowerCase())
      );

      return subjects;
    },
  },
  mounted() {
    this.syllabus.level = JSON.parse(this.$props.admin.level)[0]
    this.getSubjects();
    this.getclasses();
  },
  methods: {
    getUploadDetails(index, res,id) {
      this.syllabus.cover = res.secure_url;
    },
    cancelToggle() {
      this.$bvModal.hide("preview");
    },
    togglePreview() {
      this.showPreview = !this.showPreview;
      this.$bvModal.show("preview");
    },
    updateComment(value) {
      this.syllabus.comment = value;
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
            this.allclasses = res.data;
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

    addNew(value) {
      switch (value) {
        case 1:
          this.syllabus.learner_outcome.push({ name: "" });
          break;
        case 2:
          this.syllabus.target_skills.push({ name: "" });
          break;
        case 3:
          this.syllabus.modules.push({ name: "" });
          break;
        case 4:
          this.syllabus.delivery_methods.push({ name: "" });
          break;
        case 5:
          this.syllabus.assessments.push({ name: "" });
          break;
        case 7:
          this.syllabus.requirements.push({ name: "" });
          break;
        case 6:
          this.syllabus.faqs.push({
            answer: "",
            question: "",
          });
          break;

        default:
          break;
      }
    },
    remove(value) {
      switch (value) {
        case 1:
          this.syllabus.learner_outcome.pop();
          break;
        case 2:
          this.syllabus.target_skills.pop();
          break;
        case 3:
          this.syllabus.modules.pop();
          break;
        case 4:
          this.syllabus.delivery_methods.pop();
          break;
        case 5:
          this.syllabus.assessments.pop();
          break;
        case 7:
          this.syllabus.requirements.pop();
          break;
        case 6:
          this.syllabus.faqs.pop();
          break;

        default:
          break;
      }
    },
    submit() {
      let data = {
        syllabus: this.syllabus,
        subject: this.syllabus.subject,
        myclass: this.syllabus.grade_level,
      };
      axios
        .post("/api/syllabus", data, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.$router.push("/admin/syllabus/home");
            this.$toasted.info("Added successfully");
          }
        });
    },
  },
};
</script>

<style scoped>
.body {
  padding: 40px 30px 70px;
  position: relative;
}

form {
  padding-bottom: 60px;
  font-size: 15.5px;
}
h5 {
  margin-bottom: 24px;
}
.side-label {
  width: 150px;
}
.form-text {
  margin-bottom: 18px;
  font-size: 14px;
}
</style>