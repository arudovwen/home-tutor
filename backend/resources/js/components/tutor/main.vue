<template>
  <div>
    <b-navbar toggleable="lg" type="dark" variant="info" class="shadow-sm p-3 py-1 shadow-sm">
      <b-navbar-brand href="#" class="logo d-lg-none"> <p class="bg-white text-center rounded px-3 py-2">{{tutor.abbreviation}}</p></b-navbar-brand>

      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav class="main-nav d-lg-none ">
          <b-nav-item href="/tutor"><span class="pr-2"> <i class="icon-dashboard"></i></span>Dashboard</b-nav-item>
          <b-nav-item href="/tutor/administrative"><span class="pr-2"> <i class="icon-business-class"></i></span>Administrative</b-nav-item>
          <b-nav-item href="/tutor/activities"><span class="pr-2"> <i class="icon-market"></i></span>My Activities</b-nav-item>
          <b-nav-item href="/tutor/attendance"><span class="pr-2"> <i class="icon-edit"></i></span>Attendance</b-nav-item>
          <b-nav-item href="/tutor/resources"><span class="pr-2"> <i class="icon-open-book"></i></span>Resources</b-nav-item>
          <b-nav-item href="/tutor/assessment"><span class="pr-2"> <i class="icon-hour"></i></span>Assessment</b-nav-item>
          <b-nav-item href="/tutor/gradebook"><span class="pr-2"> <i class="icon-score"></i></span>Gradebook</b-nav-item>
          <b-nav-item href="/tutor/groups"><span class="pr-2"> <i class="icon-group-1"></i></span>Live Chat</b-nav-item>
          <b-nav-item href="/tutor/live"><span class="pr-2"> <i class="icon-teacher"></i></span>Live Class</b-nav-item>
            <b-nav-item href="#"  @click="logout">LogOut</b-nav-item>
        </b-navbar-nav>
        <b-navbar-nav class="ml-auto">
          <b-form-input placeholder="Search... " class="search rounded-pill"></b-form-input>
          <b-row class="align-items-center">
            <div id="notification" class="mx-3">
              <div class="icon" @click="toggleNotification">
                <i class="icon-bell-1"></i>
                <div class="badge animated pulse" v-if="count>0">{{count}}</div>
              </div>
              <div
                class="notification-body animated slideInDown shadow-sm p-1 bg-white"
                v-if="showNotification"
              >
                <ul class="list-group">
                  <li class="list-group-item">
                    <h6>Notifications</h6>
                  </li>

                  <div class="main-notify">
                    <li class="list-group-item" v-for="(item,idx) in notifications" :key="idx">
                      <span :class="{'text-muted':item.status}">{{item.message}}</span>
                      <small class v-if="!item.status">New</small>
                    </li>
                  </div>
                  <!-- <li class="list-group-item bg-gray text-center">
                    <small>View all</small>
                  </li> -->
                </ul>
              </div>
            </div>
            <div class="mx-3">
              <b-avatar to="/tutor/profile" size="sm" :src="tutor.profile"></b-avatar>
              <span class="toCaps mx-2">{{tutor.name}}</span>
              <i class="fa fa-ellipsis-v d-none" aria-hidden="true"></i>
            </div>
          </b-row>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
    <transition name="slide-fade">
      <router-view
        :tutor="tutor"
        class="semi-white"
        :onlineStaffs="onlineStaffs"
        :staffsMessages="staffsMessages"
        @addStaffMessage="addStaffMessage"
        :onlineGroupMembers="onlineGroupMembers"
        :groupMessages="groupMessages"
        @addGroupMessage="addGroupMessage"
        :groups="groups"
        @switchGroup="switchGroup"
        :group_id="group_id"
        :showChat="showChat"
        @updateChat="updateChat"
      :receiver_id="receiver_id"
        @addStudTutMessage="addStudTutMessage"
      :studtutMessages="studtutMessages"
      ></router-view>
    </transition>
  </div>
</template>


<script>
export default {
  props: [
    "tutor",
    "onlineStaffs",
    "staffsMessages",
    "onlineGroupMembers",
    "groupMessages",
    "groups",
    "group_id",
    "showChat",
    'studtutMessages',
    'receiver_id'
  ],
  data() {
    return {
      showNotification: false,
      notifications: [],
      count: 0,
    };
  },
  mounted() {
    Echo.private("group-created" + this.$props.tutor.id).listen(
      "GroupCreated",
      (e) => {
        this.getNotifications();
        this.$emit("updateChat");
      }
    );

    this.getNotifications();
  },

  watch: {
    $route: "reset",
  },
  methods: {
     addStudTutMessage(message, attachment) {
      this.$emit("addStudTutMessage", message, attachment);
    },
    updateChat(){
       this.$emit("updateChat");
    },
    switchGroup(id,name) {
      this.$emit("switchGroup", id,name);
    },
    addStaffMessage(message, attachment) {
      this.$emit("addStaffMessage", message, attachment);
    },
    addGroupMessage(message, attachment) {
      this.$emit("addGroupMessage", message, attachment);
    },
    initialLoad() {
      axios
        .get("/api/group", {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
          }
        });
    },
    reset() {
      this.showNotification = false;
    },
    toggleNotification() {
      this.showNotification = !this.showNotification;
      this.count = 0;
      if (!this.showNotification) {
        this.clearNotifications();
      }
    },
    getNotifications() {
      axios
        .get(`/api/tutor-notifications/${this.$props.tutor.id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            let count = [];
            this.notifications = res.data;
            this.notifications.forEach((item) => {
              if (!item.status) {
                count.push(item);
              }
            });
            this.count = count.length;
          }
        });
    },
       logout(){
          Echo.leave('online')
             this.recordTimeline('logged out')
            localStorage.removeItem('typeTutor')
            this.$router.push('/auth')
        },
    clearNotifications() {
      axios
        .get(`/api/clear-tutor-notifications/${this.$props.tutor.id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.getNotifications();
            this.count = 0;
          }
        });
    },
  },
};
</script>

<style scoped>
.semi-white {

  height: 94%; 
  overflow: auto;
  padding-bottom: 50px;
}
.bg-info{
 background: #fff !important;
}

nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.25);
  position: sticky;
  top: 0;
  background: #fff;
  z-index: 999;
}
#notification {
  position: relative;
  z-index: 999;
}
.notification-body {
  position: absolute;
  left: -290px;
  width: 300px;
}
.main-notify {
  max-height: 300px;
  overflow: scroll;
}
.icon {
  position: relative;
  font-size: 22px;
  padding-left: 10px;
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
  color: #41cee2;
}
.search {
  width: 250px;
  border-color: #41cee2;
}
.logo {
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
.navbar-dark .navbar-toggler {
    color: rgba(255, 255, 255, 0.8);
    border-color: #fff;
    outline: #fff !important;
}
ul{
  margin: 0;
}
@media screen and (max-width: 900px) {
  .bg-info{
    background:#41cee2 !important;
  }
  .notification-body{
    left: 0;
    
  }
}
</style>