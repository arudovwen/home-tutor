<template>
  <div>
    <div class>
      <b-navbar toggleable="lg" type="light" variant="faded">
        <b-navbar-brand class="d-flex align-items-center">
          <img src="/images/imsubeb.png" class="d-inline-block align-top logo mr-3" alt="logo" />
        <span class="logo-text"> E-LEARNING</span>
        </b-navbar-brand>

        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

        <b-collapse id="nav-collapse" is-nav>
          <b-navbar-nav class="mx-auto">
            <b-nav-item href="/">Home</b-nav-item>
           
              <b-nav-item href="#about">About</b-nav-item>
          
              <b-nav-item href="#sponsor">Sponsors</b-nav-item>
               <b-nav-item href="/student">Student</b-nav-item>
                <b-nav-item href="/tutor">Teacher</b-nav-item>
          
              <!-- <b-nav-item href="/contact">Contact</b-nav-item> -->
           

          </b-navbar-nav>
          <!-- Right aligned nav items -->
          <b-navbar-nav class="ml-auto" v-if="!auth">
            <router-link to="/auth" class="mr-3">
              <button v-waves.button v-waves.float v-waves.light class="login-btn nav-link">
                <span>LOGIN</span>
              </button>
            </router-link>
            <router-link to="/tutor/register">
              <button v-waves.button v-waves.float v-waves.light class="sec_color">
                <span>REGISTER</span>
              </button>
            </router-link>
          </b-navbar-nav>
          <b-navbar-nav class="ml-auto" v-if="auth">
            <b-dropdown variant="outline-success">
              <template v-slot:button-content class="d-flex align-items-center">
               
                    <b-avatar size="sm" button class="align-baseline mr-2"></b-avatar>
                    {{name}} 
                  
              </template>
              <b-dropdown-item-button @click="logout"> 
                 <span >Logout <i class="fas fa-sign-out" aria-hidden="true"></i></span></b-dropdown-item-button>
             
            </b-dropdown>
          </b-navbar-nav>
        </b-collapse>
      </b-navbar>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      auth: false,
      name: "",
      user: {}
    };
  },
  mounted() {
    let user = JSON.parse(localStorage.getItem("typeStudent"));
    if (user !== null) {
      this.name = user.name;
    }
    let tutor = JSON.parse(localStorage.getItem("typeTutor"));
    if (tutor !== null) {
      this.name = tutor.name;
      this.auth = true;
    }
  },
  watch: {},
  methods: {
    logout() {
      localStorage.removeItem("typeTutor");
      localStorage.removeItem("typeStudent");
      window.location.reload();
    }
  }
};
</script>


<style scoped lang="scss">
.logo{
  width:65px;
  color:#3b4e3a;
  font-weight: bolder;
}
a {
  text-decoration: none;
}
.login-btn {
  padding-left: 20px !important;
  padding-right: 20px !important;
  font-size: 15px;
}

.reg-btn {
  padding-left: 20px !important;
  padding-right: 20px !important;
  font-size: 15px;
}
.card {
  background-color: #f7f8fa;
  border: none;
}
.sec_color {
  background-color: var(--primary-btn);
  color: #fff;
  font-size: 16px;
}
.login-btn {
  border: 1px solid #002a06;
}
.logo-text{
  font-weight: bold;
  font-size: 18px;
}
 @media screen and (max-width: 500px){
   .link-img img{
     width: 200px;
   }
   

.login-btn {
    padding-left: 25px !important;
    padding-right: 25px !important;
    font-size: 15px;
    margin-bottom: 10px;
    color: #002a06 !important;
}
 .logo-text{
    font-size:15px !important;
  }
 }

</style>