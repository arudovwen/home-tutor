<template>
  <div class="bar">
    <div class="header">
      <nav :class="{'pl-0':sideBar}">
        <div class="logo">
          <p class="bg-white text-center rounded px-3 py-2">{{tutor.abbreviation}}</p>

          <small>SCHOOL</small>
        </div>

        <ul>
          <router-link to="/tutor">
            <li>
              <i class="icon-dashboard pr-2" aria-hidden="true"></i>
              <span v-if="!sideBar">Dashboard</span>
            </li>
          </router-link>
          <router-link to="/tutor/profile">
            <li>
              <i class="icon-group pr-2" aria-hidden="true"></i>
              <span v-if="!sideBar">My Profile</span>
            </li>
          </router-link>
          <router-link to="/tutor/administrative">
            <li>
              <i class="icon-business-class pr-2"></i>
              <span v-if="!sideBar">Administrative</span>
            </li>
          </router-link>

          <router-link to="/tutor/activities">
            <li>
              <i class="icon-market pr-2" aria-hidden="true"></i>
              <span v-if="!sideBar">My Activities</span>
            </li>
          </router-link>
          <router-link to="/tutor/attendance">
            <li>
              <i class="icon-edit pr-2"></i>
              <span v-if="!sideBar">Attendance</span>
            </li>
          </router-link>
          <router-link to="/tutor/resources">
            <li>
              <i class="icon-open-book pr-2"></i>
              <span v-if="!sideBar">Resources</span>
            </li>
          </router-link>
          <router-link to="/tutor/assessment">
            <li>
              <i class="icon-hour pr-2" aria-hidden="true"></i>
              <span v-if="!sideBar">Assessments</span>
            </li>
          </router-link>
          <router-link to="/tutor/gradebook">
            <li>
              <i class="icon-score pr-2" aria-hidden="true"></i>
              <span v-if="!sideBar">Grade Book</span>
            </li>
          </router-link>
          <router-link to="/tutor/groups">
            <li>
              <i class="icon-group-1 pr-2" aria-hidden="true"></i>
              <span v-if="!sideBar">Live Chat</span>
            </li>
          </router-link>
          <router-link to="/tutor/live">
            <li>
              <i class="icon-teacher pr-2"></i>
              <span v-if="!sideBar">Live Class</span>
            </li>
          </router-link>
           <router-link to="/tutor/insights"> <li><i class="icon-calendar  pr-2" aria-hidden="true"></i> <span v-if="!sideBar">Insights</span></li></router-link>
          
          <!-- <router-link to="/tutor/curriculum"> <li><i class="icon-dashboard1  pr-2   "></i> <span v-if="!sideBar">Curriculum</span></li></router-link>--> 
          <li @click="logout">
            <i class="icon-logout pr-2" aria-hidden="true"></i>
            <span v-if="!sideBar">Logout</span>
          </li>
        </ul>
      </nav>

      <div class="min-nav" @click="minimise">
        <i class="fas fa-compress-alt fa-1x"></i>
      </div>
    </div>
  </div>
</template>


<script>
export default {
    props:['tutor','sideBar'],
    methods: {
        logout(){
          Echo.leave('online')
             this.recordTimeline('logged out')
            localStorage.removeItem('typeTutor')
            this.$router.push('/auth')
        },
        minimise(){
            this.$emit('minimise')
        }
   
  },
};
</script>

<style scoped>
.bar {
  position: relative;
}
.min-nav {
  position: absolute;
  text-align: center;
  right: 20px;
  bottom: 40px;
}
.logo {
  padding: 10px 20px;
  margin-bottom: 20px;
  text-align: center;
}
.logo p {
  font-weight: bold;
  font-family: "Josefin Sans";
  font-size: 24px;
  text-align: center;
  color: #41cee2;
  margin: 0;
}
.logo small {
  font-size: 18px;
  color: white;
}
ul {
  list-style: none;
}
nav ul li {
  padding: 10px 20px;
  font-size: 15px;
  cursor: pointer;
  color: hsl(187deg 74% 100%);
}
nav {
  padding-left: 20px;
}
.router-link-exact-active li {
  background:#f9f7f0;
  color: #0a4065;
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
}

@media screen and (max-width: 900px) {
 
}
</style>