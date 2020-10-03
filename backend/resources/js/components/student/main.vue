<template>
  <div>
    <b-navbar toggleable="lg" type="light" variant="faded" class="shadow-sm">
      <b-navbar-brand href="/student" class="logo d-lg-none">
        <h6>{{student.abbreviation}}</h6>
      </b-navbar-brand>

      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav class="d-lg-none">
          <b-nav-item href="/student"><span class="pr-2"> <i class="icon-home-1"></i></span>Dashboard</b-nav-item>
          <b-nav-item href="/student/activities"><span class="pr-2"> <i class="icon-market"></i></span>My Activites</b-nav-item>
          <b-nav-item href="/student/explore"><span class="pr-2"> <i class="icon-refresh"></i></span>Resources</b-nav-item>
          <b-nav-item href="/student/library"><span class="pr-2"> <i class="icon-open-book"></i></span>Library</b-nav-item>
          <b-nav-item href="/student/assessment"><span class="pr-2"> <i class="icon-hour"></i></span>Assessment</b-nav-item>
          <b-nav-item href="/student/gradebook"><span class="pr-2"> <i class="icon-score"></i></span>GradeBook</b-nav-item>
          <b-nav-item href="/student/groups"><span class="pr-2"> <i class="icon-group-1"></i></span>Live Chat</b-nav-item>
          <b-nav-item href="/student/live"><span class="pr-2"> <i class="icon-business-class"></i></span>Live Class</b-nav-item>
          <b-nav-item href="#" @click="logout">LogOut</b-nav-item>
        </b-navbar-nav>
        <b-navbar-nav class="ml-auto">
          <div class="row">
            <b-nav-item class="py-0 px-2">
              <div id="notification" class>
                <div class="icon" @click="toggleNotification">
                  <i class="icon-bell-1"></i>
                  <div class="badge animated pulse" v-if="count>0">{{count}}</div>
                </div>
                <div
                  class="notification-body animated fadeIn shadow-sm p-1 bg-white"
                  v-if="showNotification"
                >
                  <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between">
                      <h6>Notifications</h6>
                      <b-icon icon="x-circle" @click="toggleNotification"></b-icon>
                    </li>

                    <div class="main-notify">
                      <li
                        class="list-group-item"
                        v-for="(item,idx) in notifications"
                        :key="idx"
                        @click="gotoHer(item)"
                      >
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
            </b-nav-item>
            <b-nav-item>
              <b-form-input placeholder="Search... " size="sm" class="search rounded-pill"></b-form-input>
            </b-nav-item>
          </div>
         
           
            <b-nav-item href="/student/profile">
              <span class="toCaps mx-2"> <b-avatar  size="" :src="student.profile"></b-avatar> {{student.name}}</span>
            </b-nav-item>
       
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
    <transition name="slide-fade">
      <router-view
        :student="student"
        :notifications="notifications"
        class="semi-white"
        :groups="groups"
        :groupMessage="groupMessage"
        :onlineMembers="onlineMembers"
        @addGroupMessage="addGroupMessage"
        @switchGroup="switchGroup"
        :currentChat="currentChat"
        :group_id="group_id"
        :classmates="classmates"
        :showChat="showChat"
        :privateMessages="privateMessages"
        @addPrivateMessage="addPrivateMessage"
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
    "studtutMessages",
    "student",
    "onlineMembers",
    "groupMessage",
    "groups",
    "currentChat",
    "group_id",
    "classmates",
    "showChat",
    "privateMessages",
    "receiver_id",
  ],
  data() {
    return {
      showNotification: false,
      notifications: [],
      count: 0,
    };
  },
  created() {
    Echo.private("group-subscribed" + this.$props.student.id).listen(
      "GroupSubscribed",
      (e) => {
        this.getNotifications();
        this.$emit("updateChat");
      }
    );
    Echo.private("resource-added" + this.$props.student.id).listen(
      "ResourceAdded",
      (e) => {
        this.getNotifications();
      }
    );
    Echo.private("deleted-group" + this.$props.student.id).listen(
      "DeletedGroup",
      (e) => {
        console.log("created -> e", e);
        this.getNotifications();
      }
    );
  },
  mounted() {
    this.getNotifications();
  },
  watch: {
    $route: "reset",
  },
  methods: {
     logout() {
      localStorage.removeItem("typeStudent");
      this.$router.push("/auth");
    },
    switchGroup(name, id) {
      this.$emit("switchGroup", name, id);
    },

    addStudTutMessage(message, attachment) {
      this.$emit("addStudTutMessage", message, attachment);
    },
    addGroupMessage(message, attachment) {
      this.$emit("addGroupMessage", message, attachment);
    },
    addPrivateMessage(message, attachment) {
      this.$emit("addPrivateMessage", message, attachment);
    },
    initialLoad() {
      axios
        .get("/api/student-group", {
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
    gotoHer(id) {
      if (id.type.toLowerCase() == "assessment") {
        this.$router.push(`/student/assessment`);
      }
      if (id.type.toLowerCase() == "group") {
        this.$router.push(`/student/groups`);
      }
      if (id.type.toLowerCase() == "resource") {
        this.$router.push(`/student/resource/view/${id.id}`);
      }
    },
    toggleNotification() {
      this.showNotification = !this.showNotification;
      this.count = 0;
      if (!this.showNotification) {
        this.clearNotifications();
      }
    },
    clearNotifications() {
      axios
        .get(`/api/clear-user-notifications/${this.$props.student.id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.getNotifications();
            this.count = 0;
          }
        });
    },
    getNotifications() {
      axios
        .get(`/api/user-notifications/${this.$props.student.id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
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
  },
};
</script>
<style scoped>
.nav {
  background: #f7f8fa;
  position: sticky;
  top: 0;
  z-index: 9;
}
.nav-item i {
  color: #808080;
  font-size: 24px;
}
.anime-stop {
  animation: none;
}

.bell {
  color: #13a699;
}
.bell:hover {
  color: #008e3a;
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
ul {
  margin: 0;
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
  font-size: 13px;
}

.list-group-item {
  font-size: 12px;
}
.logo h6 {
  font-weight: bold;
  font-family: "Josefin Sans";
  font-size: 24px;
  text-align: center;
  color: #ffd708;
  margin: 0;
}
/* .semi-white{

  padding:50px 20px;

}
nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background:white;
}

.fa-bell {
  font-size: 24px;
  color:#ffd708;
} */
.search {
  width: 250px;

  border-color: #ccc;
}
ul{
  margin: 0;
}
@media screen and (max-width: 900px) {
  .notification-body {
    left: 0;
  }
  .nav-item i{
    font-size: initial;
  }
}
</style>