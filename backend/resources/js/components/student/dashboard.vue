<template>
  <div class="my-container d-flex">
    <Side
      class="side"
      :student="student"
      :sideBar="sideBar"
      @minimise="minimise"
      :class="{'min-side':sideBar}"
    />
    <Main
      class="main"
      :student="student"
      :class="{'min-main':sideBar}"
      :groups="groups"
      :groupMessage="groupMessage"
      :group_id="group_id"
      :onlineMembers="onlineMembers"
      @addGroupMessage="addGroupMessage"
      @switchGroup="switchGroup"
      :currentChat="currentChat"
      :classmates="classmates"
      :showChat="showChat"
      :privateMessages="privateMessages"
      @addPrivateMessage="addPrivateMessage"
      :receiver_id="receiver_id"
      @updateChat="updateChat"
      @addStudTutMessage="addStudTutMessage"
      :studtutMessages="studtutMessages"
    />
  </div>
</template>


<script>
import Side from "./sideBar";
import Main from "./main";
export default {
  data() {
    return {
      student: {},
      groups: [],
      sideBar: false,
      groupMessage: [],
      onlineMembers: [],
      currentChat: "private",
      group_id: null,
      receiver_id: null,
      classmates: [],
      showChat: "private",
      privateMessages: [],
      studtutMessages: [],
    };
  },
  components: {
    Main,
    Side,
  },
  beforeRouteEnter (to, from, next) {
    next(vm=>{
       vm.student = JSON.parse(localStorage.getItem("typeStudent"));
    vm.getgroups();
    vm.getClassmates();
    vm.getPrivateMessages();
    vm.getStudTutorMessages();
    vm.joinTutStud();
    })
  },
  created() {
    this.student = JSON.parse(localStorage.getItem("typeStudent"));
    this.getgroups();
    this.getClassmates();
    this.getPrivateMessages();
    this.getStudTutorMessages();
    this.joinTutStud();
    // this.newLecture()
  },
  methods: {
    newLecture() {
      axios
        .get("/api/new-lecture", {
          headers: {
            Authorization: `Bearer ${this.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.$toasted.info("Class starting soon");
          }
        });
    },
    updateChat() {
      this.getgroups();
    },
    minimise() {
      this.sideBar = !this.sideBar;
    },
    getgroups() {
      axios
        .get("/api/student-group", {
          headers: {
            Authorization: `Bearer ${this.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            if (res.data.length) {
              this.groups = res.data;
              this.getGroupMessages(this.groups[0].id);
              res.data.forEach((group) => {
                this.joinGroup(group.name, group.id, group.tutor_id);
              });
              this.group_id = this.groups[0].id;
            }
          }
        });
    },

    getGroupMessages(id) {
      axios
        .get(`/api/get-student-messages/${id}`, {
          headers: {
            Authorization: `Bearer ${this.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.groupMessage = res.data;
          }
        });
    },
    joinGroup(name, id, tutor) {
      axios
        .get(`/api/student-group/${id}`, {
          headers: {
            Authorization: `Bearer ${this.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            Echo.join(name + id + tutor)
              .here((users) => {
                this.onlineMembers = users;
              })
              .joining((user) => {
                this.onlineMembers.push(user);
              })
              .listen("GroupMessageSent", (e) => {
                this.groupMessage.push({
                  message: e.message.message,
                  user: e.user,
                  tutor: e.tutor,
                  created_at: e.message.created_at,
                  attachment: e.message.atttachment,
                });
              })
              .leaving((user) => {
                this.onlineMembers = this.onlineMembers.filter(
                  (u) => u.id != user.id
                );
              });
          }
        });
    },
    addGroupMessage(message, attachment) {
      this.groupMessage.push({
        message: message,
        user: this.student,
        attachment: attachment,
        created_at: new Date(),
      });
    },
    addPrivateMessage(message, attachment) {
      this.privateMessages.push({
        message: message,
        attachment: attachment,
        user: this.student,
        user_id: this.student.id,
        receiver_id: this.receiver_id,
        created_at: new Date(),
      });
    },
    joinTutStud() {
      Echo.private("tutor-student").listen("TutorStudentMessage", (e) => {
        this.studtutMessages.push({
          message: e.message.message,
          receiver_id: e.message.receiver_id,
          user_id: e.message.user_id,
          created_at: e.message.created_at,
          attachment: e.message.atttachment,
        });
      });
    },
    addStudTutMessage(message, attachment) {
      this.studtutMessages.push({
        message: message,
        attachment: attachment,
        user_id: this.student.id,
        receiver_id: this.receiver_id,
        created_at: new Date(),
      });
    },
    getClassmates() {
      axios
        .get("/api/classmates", {
          headers: {
            Authorization: `Bearer ${this.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            if (res.data.length) {
              this.classmates = res.data.filter(
                (item) => item.id !== this.student.id
              );
           if(this.classmates.length){
                this.receiver_id = this.classmates[0].id;
           }
              res.data.forEach((item) => {
                Echo.private("chat" + item.id).listen(
                  "PrivateMessageSent",
                  (e) => {
                    this.privateMessages.push({
                      message: e.message.message,
                      user: e.user,
                      user_id: e.user.id,
                      receiver_id: e.receiver.id,
                      created_at: e.message.created_at,
                      attachment: e.message.atttachment,
                    });
                  }
                );
              });
            }
          }
        });
    },
    getPrivateMessages() {
      axios
        .get(`/api/private-message`, {
          headers: {
            Authorization: `Bearer ${this.student.access_token}`,
          },
        })
        .then((res) => {
          this.privateMessages = res.data;
        });
    },
    getStudTutorMessages() {
      axios
        .get(`/api/student-tutor-message`, {
          headers: {
            Authorization: `Bearer ${this.student.access_token}`,
          },
        })
        .then((res) => {
          this.studtutMessages = res.data;
        });
    },
    switchGroup(name, id) {
      if (name == "private") {
        this.showChat = "private";
        this.receiver_id = id;
      }
      if (name == "tutorstudent") {
        this.showChat = "tutorstudent";
        this.receiver_id = id;
      }
      if (name == "group") {
        this.showChat = "group";
        this.group_id = id;
        this.getGroupMessages(id);
      }
    },
  },
};
</script>

<style scoped>
.my-container {
  
  width: 100%;
  background: rgba(255, 247, 237, 0.68);
    min-height: 100vh;
}
.side {
  width: 15%;
  height: 100%;
  max-height: 100%;
  background: #ffffff;
  box-shadow: 0px 4px 9px rgba(128, 128, 128, 0.25);
  border-radius: 0px 15px 15px 0px;
  position: fixed;
}
.main {
  width: 85%;
  position: absolute;
  background: rgba(255, 247, 237, 0.68);
  right: 0;
  /* overflow: auto; */
  padding-bottom: 30px;
}
.min-side {
  width: 4% !important;
}
.min-main {
  width: 96% !important;
}
@media screen and (max-width: 900px) {
  .side {
    display: none;
  }
  .main {
    width: 100%;
    position: initial;
  }
}
</style>