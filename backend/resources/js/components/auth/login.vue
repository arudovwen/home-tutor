<template>
  <div class="main-login">
    <b-container>
      <b-row>
        <b-col md="4" sm="12" class="text-center">
          <router-link to="/" class="mb-3"> <img src="/images/imsubeb.png" class="d-inline-block align-top logo mb-3" alt="logo" /></router-link>
         <h6 >IMSUBEB E-LEARNING</h6>
        </b-col>
        <b-col md="8" sm="12">
          <b-card class="login-card">
            <h4 class="text-center">LOG IN</h4>
            <div class="login-tabs">
              <b-tabs content-class="mt-3" justified>
                <b-tab class="login" title="Student" active>
                  <b-form @submit.prevent="submit" class="login-form">
                    <b-form-group id="input-group-1" label="Email">
                      <b-form-input id="input-1" v-model="user.email" type="email" required></b-form-input>
                    </b-form-group>
                    <b-form-group id="input-group-2" label="Password">
                      <b-form-input id="input-2" v-model="user.password" type="password" required></b-form-input>
                      <button class="reg-btn" type="submit">
                        STUDENT
                        <b-spinner
                          variant
                          small
                          label="small spinner"
                          type="grow"
                          class="ml-2"
                          v-if="spin"
                        ></b-spinner>
                      </button>
                
                     <div class="text-center mt-3">  <small class=" "> <b-icon icon="info-circle-fill"></b-icon> Check your email for you login credentials</small></div>
                       <div class="my-3">  <router-link to="/auth/?password=reset&user=student">Forgot password?</router-link></div>
                    </b-form-group>
                  </b-form>
                </b-tab>
                <b-tab class="login" title="Tutor">
                  <b-form @submit.prevent="tutorLogin" class="login-form">
                    <b-form-group id="input-group-3" label="Email">
                      <b-form-input id="input-3" v-model="tutor.email" type="email" required></b-form-input>
                    </b-form-group>
                    <b-form-group id="input-group-4" label="Password">
                      <b-form-input id="input-4" v-model="tutor.password" type="password" required></b-form-input>
                      <button class="reg-btn" type="submit">
                        TUTOR
                        <b-spinner
                          variant
                          small
                          label="small spinner"
                          type="grow"
                          class="ml-2"
                          v-if="spin"
                        ></b-spinner>
                      </button>
                      <!-- <a href>forgot password?</a> -->
                      <div class="text-center mt-3">  <small class=" "> <b-icon icon="info-circle-fill"></b-icon> Check your email for you login credentials</small></div>

                         <div class="my-3">  <router-link to="/auth/?password=reset&user=tutor">Forgot password?</router-link></div>
                      
                    </b-form-group>
                  </b-form>
                </b-tab>
              </b-tabs>
            </div>
          </b-card>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>



<script>
export default {
  data() {
    return {
      user: {
        email: "",
        password: "",
      },
      tutor: {
        email: "",
        password: "",
      },
      spin: false,
    };
  },
  methods: {
    submit() {
      this.spin = true;

      let data = {
        grant_type: "password",
        client_id: 1,
        client_secret: "6jWhEe7mYzSJ2SqGEYMBAj2S50aSjf0SgkOZ9A7F",
        username: this.user.email,
        password: this.user.password,
      };

      const typeStudent = {};
      axios
        .post("/oauth/token", data)
        .then((res) => {
          if (res.status == 200) {
            typeStudent.access_token = res.data.access_token;
            typeStudent.refresh_token = res.data.refresh_token;
            axios
              .get(`/api/user`, {
                headers: { Authorization: `Bearer ${res.data.access_token}` },
              })
              .then((res) => {
                if (res.status === 200) {
               
                  this.spin = false;

                  typeStudent.id = res.data.id;
                  typeStudent.email = res.data.email;
                  typeStudent.level = res.data.student_level;
                  typeStudent.name = res.data.name;
                  typeStudent.school_id = res.data.school_id;
                  typeStudent.school = res.data.school;
                  typeStudent.abbreviation = res.data.abbreviation;
                  typeStudent.profile = res.data.profile;
                   typeStudent.sub_class = res.data.sub_class;
                         typeStudent.student_level = res.data.student_level;
                            typeStudent.course_level = res.data.course_level;
                  localStorage.setItem(
                    "typeStudent",
                    JSON.stringify(typeStudent)
                  );
                  this.$toasted.success("Logged in Sucessfully", {
                    icon: {
                      name: "fingerprint",
                    },
                  });
                  if (this.$route.query.redirect) {
                    // this.$router.push(this.$route.query.redirect);
                      window.location.href=this.$route.query.redirect
                  } else {
                    this.$toasted.info("Redirecting to dashboard..");
                    // this.$router.push("/student");
                    window.location.href="/student"
                  }
                }
              })
              .catch((error) => {
                this.$toasted.error("Invalid credentials", {
                  icon: {
                    name: "user-times",
                  },
                });
                this.spin = false;
              });
          }
        })
        .catch((error) => {
          this.$toasted.error("Invalid credentials", {
            icon: {
              name: "user-times",
            },
          });
          this.spin = false;
        });
    },
    tutorLogin() {
      this.spin = true;
      let data = {
        grant_type: "password",
        client_id: 2,
        client_secret: "EuAKZHUibmwLON4DgO1PDm9NOvhqiZk5L1aKzJVn",
        username: this.tutor.email,
        password: this.tutor.password,
      };
      const typeTutor = {};
      axios
        .post("/oauth/token", data)
        .then((res) => {
          if (res.status == 200) {
             
            typeTutor.access_token = res.data.access_token;
            typeTutor.refresh_token = res.data.refresh_token;
            axios
              .get(`/api/tutorDetails`, {
                headers: { Authorization: `Bearer ${res.data.access_token}` },
              })
              .then((res) => {
                if (res.status === 200) {
                  this.spin = false;
                  typeTutor.id = res.data.id;
                  typeTutor.email = res.data.email;
                    typeTutor.head = res.data.head;
                  typeTutor.name = res.data.name;
                  typeTutor.school = res.data.school;
                  typeTutor.abbreviation = res.data.abbreviation;
                  typeTutor.profile = res.data.profile;
                   typeTutor.courses = res.data.subjects;
                       typeTutor.course_level = res.data.course_level;
                          typeTutor.expertise = res.data.expertise;
                             typeTutor.levels = res.data.classess;
                               typeTutor.subjects = res.data.subjects;
                                typeTutor.grade_level = res.data.grade_level;
                      
                  localStorage.setItem("typeTutor", JSON.stringify(typeTutor));
                  this.$toasted.success("Logged in Sucessfully", {
                    icon: {
                      name: "fingerprint",
                    },
                  });
                 if (this.$route.query.redirect) {
                   
                      window.location.href=this.$route.query.redirect
                  } else {
                    this.$toasted.info("Redirecting to dashboard..");
                    
                    window.location.href="/tutor?redirect_from=auth"
                  }
                 
                   
                }
              })
              .catch((error) => {
                console.log("submit -> error", error);
                this.$toasted.error("Invalid credentials", {
                  icon: {
                    name: "user-times",
                  },
                });
                this.spin = false;
              });
          }
        })
        .catch((error) => {
          console.log("submit -> error", error);
          this.$toasted.error("Invalid credentials", {
            icon: {
              name: "user-times",
            },
          });
          this.spin = false;
        });
    },
  },
};
</script>

<style scoped>
label {
  position: relative;
  margin-bottom: 0;
  vertical-align: top;
  font-size: 1.25rem;
  line-height: 1.5;
}
.text-muted {
  color: rgba(0, 0, 0, 0.4) !important;
}
.logo{
  width:100px;
  
  font-weight: bolder;
}
h6{
    color: var(--primary-font-color);
}
a{
    color: var(--primary-btn) ;
}
</style>

