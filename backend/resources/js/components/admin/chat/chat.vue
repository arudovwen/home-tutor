<template>
  <div>
    <b-container>
      <b-row>
        <b-col
          cols="12"
          sm="8"
          class="p-0"
          v-if="!mobile && switchChat=='showall' || switchChat=='body'"
        >
          <div class="p-2 px-4 topbar toCaps">
            <b-icon @click="toggleChat('menu')" v-if="mobile" icon="arrow-left"></b-icon>
           <b-avatar :src="profile"  class="mr-2"></b-avatar> {{name}}
          </div>
          <ChatBody :sortedMessage="sortedMessage" />
          <Bar @submit="submit" @attach="attach" />
        </b-col>
        <b-col
          cols="12"
          sm="4"
          class="p-0 pl-sm-0"
          v-if="switchChat=='showall' || switchChat=='menu'"
        >
          <Menu @toggleChat="toggleChat" @switchName="switchName" :groupMessages="groupMessages"/>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
import ChatBody from "./chatbody";
import Menu from "./chatMenu";
import Bar from "../../chatBar";
export default {
  data() {
    return {
      admin: {},
      groupMessages: [],
      messages: [],
      message: "",
      attachment: "",
      id: null,
      name: "",
      switchChat: "menu",
      mobile: false,
      profile:''
    };
  },
  components: {
    ChatBody,
    Menu,
    Bar,
  },
  created() {
    this.admin = JSON.parse(localStorage.getItem("typeAdmin"));
    this.getMessages();
  },
  mounted() {
    this.joinChat();
    if (window.innerWidth < 900) {
      this.mobile = true;
    } else {
      this.mobile = false;
      this.switchChat = "showall";
    }
  },

  computed: {
    sortedMessage() {
      return this.groupMessages.filter(
        (item) =>
          item.tutor_id == this.id &&
          Number(item.admin_id) == Number(this.admin.id)
      );
    },
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
    joinChat() {
      Echo.private("admin-staffs").listen("AdminStaffMessages", (e) => {
       
        this.groupMessages.push({
          message: e.message.message,
          admin_id: this.admin.id,
          tutor_id: e.user.id,
          sender_id: "tutor",
          receiver_id: "admin",
          created_at: e.message.created_at,
          attachment: e.message.atttachment,
        });
      });
    },
    switchName(id, name) {
      this.id = id;
      this.name = name.name;
      this.profile = name.profile
    },
    attach(val) {
      this.attachment = val;
      this.submit(null);
    },
    getMessages() {
      axios
        .get("/api/admin-staff", {
          headers: {
            Authorization: `Bearer ${this.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.groupMessages = res.data;
          }
        });
    },
    submit(message) {
      let data = {
        message: message,
        tutor_id: this.id,
        admin_id: this.admin.id,
        sender_id: "admin",
        receiver_id: "tutor",
        attachment: this.attachment,
      };
      this.groupMessages.push(data)
     
      axios
        .post("/api/admin-staff", data, {
          headers: {
            Authorization: `Bearer ${this.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.message = "";
          }
        });
    },
  },
};
</script>

<style scoped>
.container {
  padding: 30px 0;
}
.topbar {
  background: #13a699;
  color: white;
  font-size: 16px;
}
@media screen and (max-width: 500px) {
  .container {
    padding: 0;
  }
}
</style>