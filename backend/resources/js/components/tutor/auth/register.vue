<template>
  <div>
    <!-- Tabs with card integration -->
    <b-container>
      <b-form @submit.prevent="submit">
        <b-card no-body>
          <b-tabs v-model="tabIndex" small card>
            <b-tab title="Personal Information">
              <b-form-group label="Full name">
                <b-form-input
                  placeholder="Enter full name"
                  v-model="user.personal.name"
                ></b-form-input>
              </b-form-group>
             
              <b-form-group label="Email">
                <b-form-input
                  type="email"
                  placeholder="Enter email"
                  v-model="user.personal.email"
                ></b-form-input>
              </b-form-group>
             
              <b-form-group label="Address">
                <b-form-input
                  placeholder="Enter full address"
                  v-model="user.personal.address"
                ></b-form-input>
              </b-form-group>

              <b-form-row>
                <b-col>
                   <b-form-group label="Phone Number">
                <b-form-input
                  type="tel"
                  placeholder="Enter phone number"
                  v-model="user.personal.phone"
                ></b-form-input>
              </b-form-group>
                </b-col>
             <b-col>
                 <b-form-group label="Gender">
                <b-form-select v-model="user.personal.gender">
                  <b-form-select-option value="male">Male</b-form-select-option>
                  <b-form-select-option value="female"
                    >Female</b-form-select-option
                  >
                </b-form-select></b-form-group
              >
             </b-col>
              </b-form-row>
            <b-form-row>
              <b-col>
                  <b-form-group label="State of origin">
                <b-form-input
                  placeholder="Enter state"
                  v-model="user.personal.state"
                ></b-form-input>
              </b-form-group>
              </b-col>
             <b-col>
                <b-form-group label="Local government area">
                <b-form-input
                  placeholder="Enter lga"
                  v-model="user.personal.lga"
                ></b-form-input>
              </b-form-group>
             </b-col>
            </b-form-row>
            </b-tab>
            <b-tab title="Experience">
              <b-form-group label="Grade Level">
                <b-form-input
                  placeholder="Enter grade level"
                  v-model="user.experience.grade_level"
                ></b-form-input>
              </b-form-group>
              <b-form-group label="Institution">
                <b-form-select v-model="user.experience.institution">
                  <b-form-select-option value="nursery"
                    >Nursery</b-form-select-option
                  >
                  <b-form-select-option value="primary"
                    >Primary</b-form-select-option
                  >
                  <b-form-select-option value="secondary"
                    >Secondary</b-form-select-option
                  >
                  <b-form-select-option value="tertiary">
                    Tertiary</b-form-select-option
                  >
                </b-form-select></b-form-group
              >
              <b-form-group label="Classes">
                <b-form-row>
                  <b-col
                    v-for="(item, idx) in sortClass"
                    :key="idx"
                    :value="item.class_name"
                  >
                    <b-form-checkbox
                      class="toCaps"
                      v-model="user.experience.classes"
                      :value="item.class_name"
                      >{{ item.class_name }}</b-form-checkbox
                    >
                  </b-col>
                </b-form-row>
              </b-form-group>

              <b-form-group label="Expertise">
                <b-form-row>
                  <b-col cols="6">
                    <b-form-checkbox
                      v-model="user.experience.expertise"
                      value="science"
                    >
                      Science</b-form-checkbox
                    >
                  </b-col>
                  <b-col cols="6">
                    <b-form-checkbox
                      v-model="user.experience.expertise"
                      value="art"
                    >
                      Art</b-form-checkbox
                    >
                  </b-col>
                  <b-col cols="6">
                    <b-form-checkbox
                      v-model="user.experience.expertise"
                      value="commercial"
                    >
                      Commercial</b-form-checkbox
                    >
                  </b-col>
                  <b-col cols="6">
                    <b-form-checkbox
                      v-model="user.experience.expertise"
                      value="other"
                    >
                      Other</b-form-checkbox
                    >
                  </b-col>

                </b-form-row>
                <b-form-row class="mt-2" v-if="user.experience.expertise.includes('other')">
                  <b-col cols="12" sm="6">
                    <b-form-input @change="handleOther($event)" placeholder="Specify other"></b-form-input>
                  </b-col>
                </b-form-row>
              </b-form-group>
              <b-form-group label="Subjects">
                <b-form-row v-if="sortSubject.length">
                  <b-col
                    v-for="(item, idx) in sortSubject"
                    :key="idx"
                    :value="item.class_name"
                  >
                    <b-form-checkbox
                      class="toCaps"
                      v-model="user.experience.subjects"
                      :value="item"
                      >{{ item }}</b-form-checkbox
                    >
                  </b-col>
                </b-form-row>
              </b-form-group>
            </b-tab>
            <b-tab title="Supporting documents">
              <p>Upload supporting documents for the folowing</p>
              <b-form-group label="Resume">
                <Upload :id="'resume'" @handleUpload="handleUpload" />
              </b-form-group>
              <b-form-group label="Academic Qualification(Certificates)">
                <Upload :id="'cert'" @handleUpload="handleUpload" />
              </b-form-group>
              <b-form-group label="Two Professional References">
                <ol>
                  <li class="mb-1">
                    <Upload :id="'ref1'" @handleUpload="handleUpload" />
                  </li>
                  <li><Upload :id="'ref2'" @handleUpload="handleUpload" /></li>
                </ol>
              </b-form-group>
            </b-tab>
            <b-tab title="Profile">
              <b-form-group label="Bio">
                <b-form-textarea
                  placeholder="Write bio"
                  v-model="user.profile.bio"
                ></b-form-textarea>
              </b-form-group>
              <b-form-group label="Profile picture">
                <Upload :id="'pic'" @handleUpload="handleUpload" class="mb-1" />
                <b-img
                  v-if="user.profile.profile_pic !== ''"
                  thumbnail
                  :src="user.profile.profile_pic"
                  width="100px"
                ></b-img>
              </b-form-group>
              <b-form-group label="Bank name">
                <b-form-input
                  placeholder="Enter bank name"
                  v-model="user.profile.bank_name"
                ></b-form-input>
              </b-form-group>
              <b-form-group label="Account no">
                <b-form-input
                  placeholder="Enter account no"
                  v-model="user.profile.account_no"
                ></b-form-input>
              </b-form-group>
            </b-tab>
          </b-tabs>
        </b-card>

        <!-- Control buttons-->
        <div class="d-flex justify-content-between">
          <b-button-group class="mt-2">
            <b-button size="sm" @click="tabIndex--">Previous</b-button>
            <b-button size="sm" @click="tabIndex++">Next</b-button>
          </b-button-group>
          <b-button
            v-if="tabIndex == 3"
            variant="primary"
            class="mt-2"
            size="sm"
            type="submit"
          >
            Submit</b-button
          >
        </div>
      </b-form>
    </b-container>
  </div>
</template>

<script>
import Upload from "../../ordinaryUpload";
export default {
  data() {
    return {
      tabIndex: 0,
      id: "",
      subjects: [],
      myclass: [],
      user: {
        personal: {
          name: "",
          email: "",
          gender: "male",
          phone: "",
          address: "",
          state: "",
          lga: "",
        },
        experience: {
          grade_level: "",
          institution: "nursery",
          classes: [],
          expertise: [],
          subjects: [],
        },
        documents: {
          resume: "",
          certificates: "",
          professional_ref: {
            one: "",
            two: "",
          },
        },
        profile: {
          bio: "",
          profile_pic: "",
          bank_name: "",
          account_no: "",
        },
      },
    };
  },
  components: {
    Upload,
  },
  created() {
    this.getData();
  },
  computed: {
    sortClass() {
      return this.myclass.filter((item) =>
        item.level
          .toLowerCase()
          .includes(this.user.experience.institution.toLowerCase())
      );
    },
    sortSubject() {
      if (this.myclass.length) {
        var find = this.myclass.find((item) =>
          item.level
            .toLowerCase()
            .includes(this.user.experience.institution.toLowerCase())
        );

        if (find !== null) {
          return JSON.parse(find.classsubject.subjects);
        }
       
      }
       return [];
    },
  },
  methods: {
    handleOther(e){
    
      this.user.experience.expertise.push(e)
    },
    getData() {
      axios.get("/api/reg-class").then((res) => {
        if (res.status == 200) {
          this.myclass = res.data;
        }
      });
    },
    submit() {
      axios.post("/api/create-tutor", this.user).then((res) => {
        if (res.status == 201) {
          this.$router.push('/auth')
        }

      });
    },
    handleUpload(id, res) {
      switch (id) {
        case "resume":
          this.user.documents.resume = res.secure_url;
          break;

        case "cert":
          this.user.documents.certificates = res.secure_url;
          break;

        case "ref1":
          this.user.documents.professional_ref.one = res.secure_url;
          break;

        case "ref2":
          this.user.documents.profesional_ref.two = res.secure_url;
          break;

        case "pic":
          this.user.profile.profile_pic = res.secure_url;
          break;

        default:
          break;
      }
    },
  },
};
</script>
<style scoped>
.container {
  margin-top: 50px;
}
form {
  width: 60%;
  margin: 0 auto;
}
</style>