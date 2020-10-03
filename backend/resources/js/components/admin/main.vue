<template>
  <div>
  <!-- <nav class="shadow-sm p-3">
      <b-form-input placeholder="Search... " class="search rounded-pill"></b-form-input>
       <b-row class="align-items-center">
      
          <div id="notification" class="mx-3">
          <div class="icon" >
            <i class="fa fa-bell"></i>
           
          </div>
        
        </div>
  
        
     
     <div  class="mx-3">
         <b-avatar src="" ></b-avatar> <span class="toCaps mx-2">{{admin.name}}</span>   <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
     </div>
    
      
      </b-row>
    </nav> -->
       <b-navbar toggleable="lg" type="dark" variant="info" class="shadow-sm">
      <b-navbar-brand href="/student" class="logo d-lg-none">
        <h6>{{admin.abbreviation}}</h6>
      </b-navbar-brand>

      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav class="d-lg-none">
          <b-nav-item href="/admin"> <span class="pr-2"> <i class="icon-dashboard"></i></span> Dashboard</b-nav-item>
          <b-nav-item href="/admin/classes/years"> <span class="pr-2"> <i class="icon-education"></i></span> {{JSON.parse(admin.level).includes('tertiary')?'Levels':'Classes'}}</b-nav-item>
          <b-nav-item href="/admin/courses"><span class="pr-2"> <i class="icon-edit"></i></span>Subjects</b-nav-item>
          <b-nav-item href="/admin/curriculum/home"><span class="pr-2"> <i class="icon-market"></i></span>Curriculum</b-nav-item>
          <b-nav-item href="/admin/syllabus/home"><span class="pr-2"> <i class="icon-education"></i></span>Syllabus</b-nav-item>
          <b-nav-item href="/admin/time-table/home"><span class="pr-2"> <i class="icon-calendar"></i></span>Time Table</b-nav-item>
          <b-nav-item href="/admin/tutors"><span class="pr-2"> <i class="icon-teacher"></i></span>Teachers</b-nav-item>
          <b-nav-item href="/admin/students"><span class="pr-2"> <i class="icon-students"></i></span>Students</b-nav-item>
          <b-nav-item href="/admin/resources"><span class="pr-2"> <i class="icon-open-book"></i></span>Resources</b-nav-item>
          <b-nav-item href="/admin/assessment"><span class="pr-2"> <i class="icon-hour"></i></span>Assessment</b-nav-item>
          <b-nav-item href="/admin/grade-books"><span class="pr-2"> <i class="icon-score"></i></span>GradeBook</b-nav-item>
          <b-nav-item href="/admin/chat"><span class="pr-2"> <i class="icon-group-1"></i></span>Chat</b-nav-item>
          <b-nav-item href="/admin/administrators"><span class="pr-2"> <i class="icon-admin"></i></span>Admin</b-nav-item>
          <b-nav-item href="#"  @click="logout">LogOut</b-nav-item>
        </b-navbar-nav>
        <b-navbar-nav class="ml-auto">
          <div class="row align-items-center px-3">
            <b-nav-item class="py-0 px-2">
                <div class="icon">
                  <i class="icon-bell-1"></i>
              </div>
            </b-nav-item>
            <b-nav-item>
              <b-form-input placeholder="Search... " size="sm" class="search rounded-pill"></b-form-input>
            </b-nav-item>
             <router-link to="/admin/profile">
              <div class="toCaps admin-name"> <b-avatar  size="sm" class="" :src="admin.profile"></b-avatar> {{admin.name}}</div></router-link>
          </div>
        
           
               
            
           
        
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
    <transition name="slide-fade">
      <router-view :admin="admin"  :onlineTutors="onlineTutors" class="semi-white"></router-view>
    </transition>
  </div>
</template>


<script>
export default {
  props:['onlineTutors'],
  data() {
    return {
      admin: {},
    };
  },
  created() {
    this.admin = JSON.parse(localStorage.getItem("typeAdmin"));
  },
  methods: {
     logout() {
        Echo.leave('online')
      localStorage.removeItem("typeAdmin");
      this.$router.push("/admin/auth/login");
    },
  },
};
</script>
<style scoped>
.semi-white{
 
  height: 100%;
 
}
nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
#notification {
  position: relative;
  z-index: 999;
}
.notification-body {
  position: absolute;
  left: -240px;
  width: 250px;
}
.main-notify {
  max-height: 300px;
  overflow: scroll;
}
.icon {
  position: relative;
}
.badge {
  position: absolute;
  top: -10%;
  left: 50%;
  background: red;
  color: white;
  font-size: 14px;
}

.list-group-item {
  font-size: 14px;
}
.fa-bell {
  font-size: 24px;
  color:#008E3A;
}
.search{
  width:250px;
  border-color:#008E3A
}
.bg-info{
  background: #fff !important;
}
.admin-name{
  color:#333;
}
@media screen and (max-width: 900px){
  .bg-info{
   background-color:#008E3A !important ;
  }
}
</style>