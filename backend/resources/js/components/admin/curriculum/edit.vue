<template>
  <div class="body" :class="{'overflow-hide':showPreview}">
    <form @submit.prevent="togglePreview">
      <legend class="text-center">Update Curriculum</legend>
      <b-row>
        <b-col>
          <b-form-group label="Category">
            <b-form-select v-model="curriculum.level" class="w-50">
              <b-form-select-option value="selected">Select</b-form-select-option>
              <b-form-select-option
                class="toCaps"
                :value="level"
                v-for="(level,idx) in JSON.parse(admin.level)"
                :key="idx"
              >{{level.toUpperCase()}}</b-form-select-option>
            </b-form-select>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group :label="JSON.parse(admin.level).includes('tertiary')?'Level':'Class'">
            <b-form-select required class="custom-select w-50" v-model="curriculum.grade_level">
              <option selected disabled value>Select one</option>
              <b-form-select-option
                :value="item.class_name.toLowerCase().trim()"
                v-for="(item,idx) in sortedClass"
                class="toCaps"
                :key="idx"
              >{{item.class_name.toLowerCase().trim()}}</b-form-select-option>
            </b-form-select>
          </b-form-group>
        </b-col>
      </b-row>

      <div class="border p-3 my-4">
        <h5>Student Profile</h5>

        <div class="form-group d-flex">
          <label for class="mr-3 side-label">Target Age</label>
          <input
            required
            v-model="curriculum.profile.age"
            type="text"
            class="form-control w-75"
            aria-describedby="helpId"
            placeholder="1 to 5"
          />
        </div>
        <div class="form-group d-flex">
          <label for class="mr-3 side-label">Interests</label>
          <input
            required
            v-model="curriculum.profile.interests"
            type="text"
            class="form-control w-75"
            aria-describedby="helpId"
            placeholder="Science , Sales .."
          />
        </div>
        <div class="form-group d-flex">
          <label for class="mr-3 side-label">Learning Abilities</label>
          <input
            required
            type="text"
            class="form-control w-75"
            aria-describedby="helpId"
            v-model="curriculum.profile.abilities"
            placeholder
          />
        </div>
        <div class="form-group d-flex">
          <label for class="mr-3 side-label">Other Pecularities</label>

          <textarea required class="form-control w-75" rows="3" v-model="curriculum.profile.others"></textarea>
        </div>
      </div>

      <div class="border p-3 my-4">
        <h5>Overarching Learner Outcome</h5>

        <div class="form-group">
          <small
            id="helpId"
            class="form-text text-muted"
          >What do you intend for students to learn at the end of the session?</small>
          <span v-for="(item,idx) in curriculum.learner_outcome" :key="idx" class="mb-2 d-flex">
            <span class="mr-3">{{idx+1}}.</span>
            <input
              required
              type="text"
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
              v-if="curriculum.learner_outcome.length > 1"
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

        <span v-for="(item,idx) in curriculum.target_skills" :key="idx" class="mb-2 d-flex">
          <span class="mr-3">{{idx+1}}.</span>
          <input
            required
            type="text"
            class="form-control w-25 mb-2"
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
            v-if="curriculum.target_skills.length > 1"
          >Remove</b-button>
        </div>
      </div>
      <div class="border p-3 my-4">
        <h5>Required subjects</h5>
        <small
          id="helpId"
          class="form-text text-muted"
        >What subjects are your students required to learn this session</small>
        <div v-if="sortedSubject.length">
          <span
            v-for="(item,idx) in JSON.parse(sortedSubject[0].classsubject.subjects)"
            :key="idx"
            class="mb-2 d-flex"
          >
            <span class="mr-3">{{idx+1}}.</span>
            <p class="toCaps mb-2">{{item}}</p>
          </span>
        </div>
      </div>
      <div class="border p-3 my-4">
        <h5>Delivery Methods</h5>
        <small
          id="helpId"
          class="form-text text-muted"
        >What delivery methods are the teachers expected to employ in teaching the students?</small>

        <span v-for="(item,idx) in curriculum.delivery_methods" :key="idx" class="mb-2 d-flex">
          <span class="mr-3">{{idx+1}}.</span>
          <input
            required
            type="text"
            class="form-control w-25 mb-2"
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
            v-if="curriculum.delivery_methods.length > 1"
          >Remove</b-button>
        </div>
      </div>
      <div class="border p-3 my-4">
        <h5>Assessment</h5>
        <small id="helpId" class="form-text text-muted">How will the students be assessed?</small>

        <span v-for="(item,idx) in curriculum.assessments" :key="idx" class="mb-2 d-flex">
          <span class="mr-3">{{idx+1}}.</span>
          <input
            required
            type="text"
            class="form-control w-25 mb-2"
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
            v-if="curriculum.assessments.length > 1"
          >Remove</b-button>
        </div>
      </div>
      <div>
        <h5>FAQs (Optional)</h5>

        <div v-for="(item,idx) in curriculum.faqs" :key="idx" class="mb-3">
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
          <hr v-if="curriculum.faqs.length > 1" />
        </div>
        <div class="d-flex">
          <b-button type="button" class="mr-3" @click="addNew(6)">Add</b-button>
          <b-button type="button" class @click="remove(6)" v-if="curriculum.faqs.length > 1">Remove</b-button>
        </div>
      </div>
      <div class="border p-3 my-4">
        <h5>Course Availability</h5>

        <div class="form-group">
          <label for></label>
          <select required class="custom-select w-25" v-model="curriculum.availability">
            <option selected value disabled>Select one</option>
            <option value="online">Online</option>
            <option value="offline">Offline</option>
            <option value="both">Both</option>
          </select>
        </div>
      </div>

      <div class="border p-3 my-4">
        <h5>Comments</h5>
        <div class="form-group">
          <textarea
            class="form-control"
            v-model="curriculum.comments"
            rows="3"
            placeholder="write comment here ..."
          ></textarea>
        </div>
      </div>
      <div class="my-5">
        <b-button type="submit">Update</b-button>
      </div>
    </form>
    <div class="popup-overlay" v-if="showPreview">
      <Preview
        class="preview"
        :curriculum="curriculum"
        @submit="submit"
        @togglePreview="togglePreview"
        :afterSubmit="afterSubmit"
      />
    </div>
  </div>
</template>

<script>
import Preview from "./preview";
export default {
  props: ["admin"],
  data() {
    return {
    
      showPreview: false,
      afterSubmit: false,
      allclasses: [],
      curriculum: {
          level: "select",
        grade_level: "",
        profile: {
          age: "",
          interests: "",
          abilities: "",
          others: "",
        },
        learner_outcome: [{ name: "" }],
        target_skills: [{ name: "" }],
        prerequisite: [],
        delivery_methods: [{ name: "" }],
        assessments: [{ name: "" }],
        faqs: [
          {
            question: "",
            answer: "",
          },
        ],
        availability: "",
        comments: "",
      },
    };
  },
  components: {
    Preview,
  },
  watch: {
    "curriculum.grade_level": "assignsubject",
  },
  mounted() {
    this.getclasses();
    this.getCurriculum();
  },
  computed: {
    sortedSubject() {
      var subjects = this.sortedClass.filter((item) =>
        item.class_name
          .toLowerCase()
          .includes(this.curriculum.grade_level.toLowerCase())
      );

      return subjects;
    },
    sortedClass() {
      var subjects = this.allclasses.filter((item) =>
        item.level.toLowerCase().includes(this.curriculum.level.toLowerCase())
      );

      return subjects;
    },
  },
  methods: {
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
    addNew(value) {
      switch (value) {
        case 1:
          this.curriculum.learner_outcome.push({ name: "" });

          break;
        case 2:
          this.curriculum.target_skills.push({ name: "" });

          break;
        case 3:
          this.curriculum.prerequisite.push({ name: "" });

          break;
        case 4:
          this.curriculum.delivery_methods.push({ name: "" });
          break;
        case 5:
          this.curriculum.assessments.push({ name: "" });
          break;
        case 6:
          this.curriculum.faqs.push({
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
          this.curriculum.learner_outcome.pop();
          break;
        case 2:
          this.curriculum.target_skills.pop();
          break;
        case 3:
          this.curriculum.prerequisite.pop();
          break;
        case 4:
          this.curriculum.delivery_methods.pop();
          break;
        case 5:
          this.curriculum.assessments.pop();
          break;
        case 6:
          this.curriculum.faqs.pop();
          break;

        default:
          break;
      }
    },
    assignsubject() {
      this.curriculum.prerequisite = JSON.parse(
        this.sortedSubject[0].classsubject.subjects
      );
    },
    submit() {
      let data = {
        curriculum: this.curriculum,
        subject: this.curriculum.grade_level,
      };
      axios
        .put(`/api/curriculum/${this.$route.params.id}`, data, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.$router.push("/admin/curriculum/home");
            this.$toasted.info("Updated successfully");
          }
        });
    },
    getCurriculum() {
      axios
        .get(`/api/curriculum/${this.$route.params.id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.curriculum = JSON.parse(res.data.curriculum);
           
          }
        });
    },
    togglePreview() {
      this.showPreview = !this.showPreview;
    },
  },
};
</script>
<style scoped>
.body {
  padding: 40px 30px 70px;
  position: relative;
}
.overflow-hide {
  overflow: hidden;
  height: 100vh;
}
.preview {
  z-index: 2;
}
.popup-overlay {
  position: absolute;
  width: 100%;
  height: 100vh;
  background: rgba(0, 0, 0, 0.7);
  z-index: 1;
  top: 0;
  left: 0;
  overflow: scroll;
  padding: 20px;
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
</style>