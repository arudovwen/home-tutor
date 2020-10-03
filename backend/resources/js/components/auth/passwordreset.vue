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
            
            <div class="login-tabs">
              <b-tabs content-class="mt-3" justified>
                <b-tab class="login" title="Password reset" active>
                  <b-form @submit.prevent="submit" class="login-form">
                        <b-form-group>
                            <b-alert :show="show" :variant="result=='success'?'success':'danger'"><b-icon  :icon="result=='success'?'check-circle':'x-circle'" class="green"></b-icon> {{result=='success'?'Password reset sent to your email':'Email not registered !!'}}</b-alert>
                        </b-form-group>
                    <b-form-group id="input-group-1" label="Email">
                      <b-form-input id="input-1" v-model="user.email" type="email" placeholder="Enter your email" required></b-form-input>
                    </b-form-group>
                    <b-form-group id="input-group-2">
                    
                      <button class="reg-btn" type="submit">
                        Submit
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
                     <div class="text-center"><router-link to="/auth">Login</router-link></div>
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
        user:''
      
      },
     result:null,
      spin: false,
    show:false
    };
  },
  methods: {
      submit(){
          this.spin = true
          this.user.user = this.$route.query.user
          axios.post('/api/reset-password',this.user).then(res=>{
              if(res.status==200){
                  
                       this.result = res.data.status
                   this.spin = false
                     this.show = true
                     this.user.email = ''
              }
          }).catch(err=>{
              this.spin = false
          })
      }
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

