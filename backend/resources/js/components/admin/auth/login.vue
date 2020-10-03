<template>
  <div class="main-body">
    <div class="bg-img">
      <img src="/images/admin-bgimg.png" alt="" />
    </div>
    <div class="admin-login">
      <div class="card w-50">
        <div class="header-logo text-center">
          <router-link to="/" class="mb-3">
            <img
              src="/images/imsubeb.png"
              class="d-inline-block align-top logo mb-3"
              alt="logo"
            />
          </router-link>
          <h6>IMSUBEB E-LEARNING</h6>
        </div>
        <b-container class="bv-example-row admin-form">
          <b-form @submit.prevent="submit">
            <legend class="text-center">ADMIN LOGIN</legend>
            <b-form-row>
              <b-col cols="12">
                <b-form-group
                  id="input-group-2"
                  label="Email "
                  label-for="input-2"
                >
                  <b-form-input
                    id="input-8"
                    type="email"
                    v-model="admin.email"
                    required
                    placeholder
                  ></b-form-input>
                </b-form-group>
                <b-form-group
                  id="input-group-3"
                  label="Password"
                  label-for="input-3"
                >
                  <b-form-input
                    id="input-3"
                    type="password"
                    v-model="admin.password"
                    required
                    placeholder
                  ></b-form-input>
                </b-form-group>
              </b-col>
            </b-form-row>

            <b-form-row>
              <b-col>
                <b-form-group>
                  <button class="reg-btn btn-block ml-0" type="submit">
                    ADMIN LOGIN
                    <b-spinner
                      variant
                      small
                      label="small spinner"
                      type="grow"
                      class="ml-2"
                      v-if="spin"
                    ></b-spinner>
                  </button>
                </b-form-group>
              </b-col>
            </b-form-row>
          </b-form>
        </b-container>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      admin: {
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
        client_id: 3,
        client_secret: "2kXjKfg0BTDtPLTpIkXXU4EgG58UpMlpqronEcs4",
        username: this.admin.email,
        password: this.admin.password,
      };
      const typeAdmin = {};
      axios
        .post("/oauth/token", data)
        .then((res) => {
          if (res.status == 200) {
            typeAdmin.access_token = res.data.access_token;
            typeAdmin.refresh_token = res.data.refresh_token;
            axios
              .get(`/api/adminDetails`, {
                headers: { Authorization: `Bearer ${res.data.access_token}` },
              })
              .then((res) => {
                if (res.status === 200) {
                  this.spin = false;
                  if (res.data.verify == 1) {
                    typeAdmin.id = res.data.id;
                    typeAdmin.email = res.data.email;
                    typeAdmin.abbreviation = res.data.abbreviation;
                    typeAdmin.name = res.data.name;
                    typeAdmin.school_id = res.data.school_id;
                    typeAdmin.school = res.data.school;
                    typeAdmin.role = res.data.role;
                    typeAdmin.level = res.data.schools.level;
                    localStorage.setItem(
                      "typeAdmin",
                      JSON.stringify(typeAdmin)
                    );
                    this.$toasted.success("Logged in Sucessfully", {
                      icon: {
                        name: "fingerprint",
                      },
                    });
                    if (this.$route.query.redirect) {
                      window.location.href = this.$route.query.redirect;
                    } else {
                      this.$toasted.info("Redirecting to dashboard..", {
                        icon: {
                          name: "hourglass",
                        },
                      });
                      window.location.href = "/admin";
                    }
                  } else {
                    this.$toasted.info("Subscribe to access account");

                    this.$router.push(
                      "/checkout?redirection_from=registration"
                    );
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
  },
};
</script>

<style scoped>
.main-body {
  position: relative;
  height: 100vh;
}
.bg-img {
  position: absolute;
  bottom: 0;
  left: 0;
}
.admin-login {
  display: flex;
  justify-content: center;
  padding: 50px 0;
}
.header-logo img {
  width: 150px;
}
.card {
  box-shadow: 0px 4px 24px rgba(0, 0, 0, 0.25);
}
.w-50 {
  width: 40% !important;
}
@media screen and (max-width: 768px) {
  .w-50 {
    width: 100% !important;
  }
  .admin-login {
    padding: 30px;
    padding-top: 5rem;
  }
  .img-side {
    display: none;
  }
  .form-side {
    width: 100%;
  }
  form {
    width: 100%;
  }
  .form-side h2 {
    margin-top: 0;
  }
  .admin-form {
    display: initial;
  }
}
</style>