<template>
  <div class="chat">
    <b-container fluid class="card tutor-chat">
      <b-row>
        <b-col
          md="8"
          class="chat-area p-0 pr-sm-0"
          v-if="!mobile && switchChat=='showall' || switchChat=='body'"
        >
          <div class="p-2 px-4 topbar toCaps">
            <b-icon @click="toggleChat('menu')" v-if="mobile" icon="arrow-left"></b-icon>
            <b-avatar :src="profile" class="mr-2"></b-avatar>{{name}}
          </div>
          <GroupChat :tutor="tutor" :groupMessages="groupMessages" v-if="showChat=='group'" />
          <staffsChat
            v-if="showChat=='staff'"
            :tutor="tutor"
            :onlineStaffs="onlineStaffs"
            :staffsMessages="staffsMessages"
          />

          <TutorStudent
            v-if="showChat=='tutorstudent'"
            :tutor="tutor"
            :studtutMessages="studtutMessages"
            :receiver_id="receiver_id"
          />

          <AdminBody :adminMessages="adminMessages" :tutor="tutor" :id="group_id" v-if="showChat=='adminChat'"/>
          <ChatBar @submit="submit" @attach="attach" />
        </b-col>
        <ChatMenu
          v-if="switchChat=='showall' || switchChat=='menu'"
          @newGroup="newGroup"
          :groups="groups"
          @switchGroup="switchGroup"
          @online="online"
          @drop="drop"
          :tutor="tutor"
          :studtutMessages="studtutMessages"
          :adminMessages="adminMessages"
          @toggleChat="toggleChat"
        />
      </b-row>
    </b-container>

    <b-modal id="online-group" title="Online Users" hide-footer>
      <div class="text-center">
        <b-list-group class="text-center" v-if="showChat=='staff'">
          <b-list-group-item
            v-for="(user,id) in onlineStaffs"
            :key="id"
            class="toCaps"
          >{{user.name}}</b-list-group-item>
        </b-list-group>
        <b-list-group class="text-center" v-else>
          <b-list-group-item
            v-for="(user,id) in onlineGroupMembers"
            :key="id"
            class="toCaps"
          >{{user.name}}</b-list-group-item>
        </b-list-group>
      </div>
    </b-modal>
  </div>
</template>

<script>
import ChatBar from "../chatBar";
import ChatMenu from "../chatMenu";
import GroupChat from "./groupChat";
import StaffsChat from "./staffsChat";
import TutorStudent from "./tutorstudent";
import AdminBody from './adminbody'
export default {
  props: [
    "tutor",
    "onlineGroupMembers",
    "groupMessages",
    "groups",
    "group_id",
    "showChat",
    "onlineStaffs",
    "staffsMessages",
    "studtutMessages",
    "receiver_id",
  ],
  data() {
    return {
      messages: [],
      message: "",
      attachment: "",
      users: [],
      myText: "",
      name: "",
      switchChat: "menu",
      mobile: false,
      adminMessages: [],
      profile:''
    };
  },
  components: {
    ChatBar,
    ChatMenu,
    GroupChat,
    StaffsChat,
    TutorStudent,
    AdminBody
  },

  mounted() {
    if (window.innerWidth < 900) {
      this.mobile = true;
    } else {
      this.mobile = false;
      this.switchChat = "showall";
    }
    this.joinAdminChat(),
    this.getAdminMessages()
  },
  methods: {
     joinAdminChat() {
      Echo.private("admin-staffs").listen("AdminStaffMessages", (e) => {
       
        this.adminMessages.push({
          message: e.message.message,
          admin_id: e.user.id,
          tutor_id: this.$props.tutor.id,
          sender_id: "admin",
         receiver_id: "tutor",
          created_at: e.message.created_at,
          attachment: e.message.atttachment,
        });
      });
    },
    getAdminMessages() {
      axios
        .get("/api/admin-staff", {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.adminMessages = res.data;
          }
        });
    },
    toggleChat(key) {
      if (window.innerWidth < 900) {
        switch (key) {
          case "all":
            this.switchChat = "showall";
            this.mobile = false;
            break;

          case "menu":
            this.switchChat = "menu";
            break;

          case "body":
            this.switchChat = "body";
            break;

          default:
            break;
        }
      }
    },
   
    addStudTutMessage(message, attachment) {
      this.$emit("addStudTutMessage", message, attachment);
    },
    drop(val) {
      this.$emit("drop", val);
    },
    newGroup() {
      this.$emit("newGroup");
    },
    switchGroup(id, name, val) {
     
      if(val == 'staff'){
        this.name = val;
     }else{
        this.name = val.name;
        this.profile = val.profile
     }
      this.$emit("switchGroup", id, name);
    },
    online() {
      this.$bvModal.show("online-group");
    },
    attach(val) {
      this.attachment = val;
      this.submit(null);
    },

    submit(message) {
      if (this.$props.showChat == "group") {
        this.$emit("addGroupMessage", message, this.attachment);
        let data = {
          message: message,
          group_id: this.$props.group_id,
          attachment: this.attachment,
        };
        axios
          .post("/api/send-message", data, {
            headers: {
              Authorization: `Bearer ${this.$props.tutor.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.message = "";
            }
          });
      }

      if (this.$props.showChat == "staff") {
        this.$emit("addStaffMessage", message, this.attachment);
        let data = {
          message: message,
          attachment: this.attachment,
          receiver_id: this.$props.receiver_id,
        };

        axios
          .post("/api/staff-message", data, {
            headers: {
              Authorization: `Bearer ${this.$props.tutor.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.message = "";
            }
          });
      }
      if (this.$props.showChat == "tutorstudent") {
        this.$emit("addStudTutMessage", message, attachment);
        let data = {
          message: message,
          attachment: this.attachment,
          user_id: this.$props.receiver_id,
        };
        axios
          .post("/api/tutor-student-message", data, {
            headers: {
              Authorization: `Bearer ${this.$props.tutor.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.message = "";
            }
          });
      }

       if (this.$props.showChat == "adminChat") {
       let data = {
        message: message,
        tutor_id: this.$props.tutor.id,
        admin_id: this.$props.group_id,
        sender_id: "tutor",
        receiver_id: "admin",
        attachment: this.attachment,
         created_at: new Date(),
      };
      this.adminMessages.push(data)
        axios
          .post("/api/staff-admin", data, {
            headers: {
              Authorization: `Bearer ${this.$props.tutor.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.message = "";
            }
          });
      }
    },
  },
};
</script>
<style scoped>
.chat .card {
  /* background-image: linear-gradient(to bottom, rgba(34, 202, 222, 0.15), rgba(34, 202, 222, 0.15)), url(/images/patterns.jpg); */
}
.view {
  background: white;
  position: relative;
  display: flex;
  height: 84vh;
}
.online-presence {
  background: #f5f4f4;
  height: 80vh;
}
.online-presence-top {
  display: flex;
}
.online-presence-top .fa {
  padding-left: 10px;
  padding-top: 10px;
}
.inner-single {
  display: flex;
}
.message-info {
  display: flex;
  padding-left: 20px;
  align-items: center;
}
.message-info h6 {
  margin-bottom: 0;
  line-height: 0;
}
.file-attachement {
  display: flex;
  width: 100%;
  position: relative;
}
.file-attachement span {
  position: absolute;
  right: 0;
  transform: translate(-7px);
  padding-top: 5px;
}
.progress {
  height: 15px;
}
.attach {
  border-radius: 6px;
  padding: 10px;
}
label {
  margin: 0 !important;
  display: block;
}
.message {
  font-size: 14px;
  padding: 0 20px;
}
.chat-body {
  width: 100%;
  height: 80vh;
  padding: 20px 15px 60px;
  position: relative;
  overflow: auto;
  background: hsl(187deg 74% 99%);
}
ul{
  margin: 0;
}
.online {
  height: 100%;
  width: 20%;
}
.chat-area {
  position: relative;
}
.send-tab {
  position: absolute;
  bottom: 0;
  background: #cec9c9;
  display: flex;
  width: 100%;
  padding: 10px;
}

.emoji {
  position: absolute;
  bottom: 100%;
  overflow: hidden !important;
}
.online ul li {
  font-size: 15px;
  padding: 10px 5px;
}
.form-group {
  position: relative;
}

ul,
ol {
  list-style: none;
}
.chat-message {
  background: linear-gradient(
    to bottom,
    rgba(34, 202, 222, 0.15),
    rgba(34, 202, 222, 0.15)
  );
  padding: 15px 20px;
  width: fit-content;
  width: max-content;
  max-width: 50%;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
  text-align: left;
}
.topbar {
  background: #41cee2;
  color: white;
  font-size: 16px;
}
@media screen and (max-width: 500px) {
  .topbar {
    background: #ccc;
  }
  .send-tab {
    position: fixed;
  }
}
</style>