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
            <b-avatar v-if="name" :src="profile" class="mr-2"></b-avatar>{{name}}
          </div>
          <ChatBody
            :student="student"
            :groupMessage="groupMessage"
            :onlineMembers="onlineMembers"
            v-if="showChat =='group'"
          />
          <PrivateChat
            :student="student"
            v-if="showChat=='private'"
            :privateMessages="privateMessages"
            :receiver_id="receiver_id"
          />
          <TutorStudent
            :student="student"
            v-if="showChat=='tutorstudent'"
            :receiver_id="receiver_id"
            :studtutMessages="studtutMessages"
          />
          <ChatBar @submit="submit" @attach="attach" />
        </b-col>
        <ChatMenu
          v-if="switchChat=='showall' || switchChat=='menu'"
          :student="student"
          :groups="groups"
          @switchGroup="switchGroup"
          @online="online"
          :classmates="classmates"
          :receiver_id="receiver_id"
          :privateMessages="privateMessages"
          :studtutMessages="studtutMessages"
          @toggleChat="toggleChat"
        />
      </b-row>
    </b-container>

    <b-modal id="online" title="Online Users" hide-footer>
      <div class="text-center">
        <router-link to>Create Group</router-link>
        <b-list-group class="text-center">
          <b-list-group-item
            v-for="(user,id) in onlineMembers"
            :key="id"
            class="toCaps"
            @click="openChat(user)"
          >{{user.name}}</b-list-group-item>
        </b-list-group>
      </div>
    </b-modal>
  </div>
</template>

<script>
import ChatBar from "../chatBar";
import ChatMenu from "./chatMenu";
import ChatBody from "./groupBody";
import PrivateChat from "./privateChat";
import TutorStudent from "./tutorstudent";
export default {
  props: [
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
    "studtutMessages",
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
      profile:''
    };
  },
  components: {
    ChatBar,
    ChatMenu,
    ChatBody,
    PrivateChat,
    TutorStudent,
  },

  mounted() {
    if (window.innerWidth < 900) {
      this.mobile = true;
    } else {
      this.mobile = false;
      this.switchChat = "showall";
    }
    
  },
  methods: {
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
    switchGroup(name, id, val) {
     
      this.$emit("switchGroup", name, id);
      this.name = val.name;
      console.log("switchGroup -> val.name", val.name)
      console.log("switchGroup -> this.name", this.name)
      this.profile = val.profile
    },

    online() {
      this.$bvModal.show("online");
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
          .post("/api/send-student-message", data, {
            headers: {
              Authorization: `Bearer ${this.$props.student.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.message = "";
            }
          });
      }
      if (this.$props.showChat == "private") {
        this.$emit("addPrivateMessage", message, this.attachment);

        let data = {
          message: message,
          receiver_id: this.$props.receiver_id,
          attachment: this.attachment,
        };
        axios
          .post("/api/private-message", data, {
            headers: {
              Authorization: `Bearer ${this.$props.student.access_token}`,
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
          tutor_id: this.$props.receiver_id,
        };
        axios
          .post("/api/student-tutor-message", data, {
            headers: {
              Authorization: `Bearer ${this.$props.student.access_token}`,
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
.view {
  background: white;
  position: relative;
  display: flex;
  height: 84vh;
}
.online-presence {
  background: #f5f4f4;
  height: 85vh;
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
  padding: 20px 0 60px;
  position: relative;
  overflow: auto;
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
  background: rgba(255, 247, 237, 0.68);
  padding: 15px 20px;
  width: fit-content;
  width: max-content;
  max-width: 50%;

  text-align: left;
}
.topbar {
  background: #13a699;
  color: white;
  font-size: 16px;
}
@media screen and (max-width: 500px){
.send-tab{
  position: fixed;
}
}
</style>