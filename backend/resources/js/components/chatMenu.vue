<template>
  <b-col md="4" class="p-0 pl-sm-0">
    <b-card class="online-presence">
      <div class="online-presence-top mb-4">
        <b-form-input placeholder="Search to start chat" v-model="search"></b-form-input>
        <span class="px-2">
          <b-icon icon="three-dots-vertical" id="popover-button-event" size="1.2rem"></b-icon>
        </span>
        <b-popover ref="popover" target="popover-button-event">
          <ul class="pop">
            <li @click="newGroup">New Group</li>
            <li @click="online">Who's Online?</li>
          </ul>
        </b-popover>
      </div>

      <div
        class="single-online-tag mb-4 px-0"
       
        @click="switchGroup('staff','staff','staff')"
      >
        <div class="inner-single">
          <div class="message-info">
            <strong class="toCaps">
              <b-avatar text="S" class="mr-2" size="1.3rem"></b-avatar> Staffs
            </strong>
          </div>
          <!-- <div>
            <b-badge variant="success">3</b-badge>
          </div>-->
        </div>
      </div>

      <div class="online-tag">
        <div
          class="mb-4 d-flex justify-content-between align-items-center"
          @click="handleToggles('groups')"
        >
          <strong class="text-muted">
            <b-avatar text="G" class="mr-2" size="1.3rem"></b-avatar> Groups
          </strong>
          <b-icon icon="chevron-down" v-if="!toggleGroups"></b-icon>
          <b-icon v-else @click="handleToggles('groups')" icon="chevron-up"></b-icon>
        </div>
        <div v-if="toggleGroups" class="mb-4">
          <div
            class="single-online-tag"
            :class="{active:active==item.name}"
            @click="switchGroup(item.id,'group',item)"
            v-for="(item,idx) in sortedGroups"
            :key="idx"
          >
            <div class="inner-single">
              <div class="message-info">
                <div class="avatar mr-3">
                  <b-avatar></b-avatar>
                </div>
                <div>
                  <p class="toCaps name">{{item.name}}</p>

                  <small
                    v-if="item.messages.length"
                  >{{item.messages[item.messages.length-1].message}}</small>
                </div>
              </div>
              <div>
                <b-badge variant="success">
                  <b-icon class icon="x" @click="drop(item.id)"></b-icon>
                </b-badge>
              </div>
            </div>
          </div>
        </div>

        <div
          class="mb-4 d-flex justify-content-between align-items-center"
          @click="handleToggles('tutors')"
        >
          <strong class="text-muted">
            <b-avatar text="S" class="mr-2" size="1.3rem"></b-avatar> Students
          </strong>
          <b-icon icon="chevron-down" v-if="!toggleTutors"></b-icon>
          <b-icon v-else @click="handleToggles('tutors')" icon="chevron-up"></b-icon>
        </div>
        <div v-if="toggleTutors" class="mb-4">
          <div
            class="single-online-tag"
            v-for="(item) in sortedMessage()"
            :key="item.id"
            :class="{active:active==item.name}"
            @click="switchGroup(item.id,'tutorstudent',item)"
          >
            <div class="inner-single">
              <div class="message-info">
                <div class="avatar mr-3">
                  <b-avatar :src="item.profile"></b-avatar>
                </div>
                <div>
                  <p class="toCaps name">{{item.name}}</p>

                  <small
                    v-if="sortedMessages(item.id).length"
                  >{{sortedMessages(item.id)[sortedMessages(item.id).length - 1].message}}</small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div
          class="mb-4 d-flex justify-content-between align-items-center"
          @click="handleToggles('admin')"
        >
          <strong class="text-muted">
            <b-avatar text="S" class="mr-2" size="1.3rem">A</b-avatar> Admins
          </strong>
          <b-icon icon="chevron-down" v-if="!toggleAdmin"></b-icon>
          <b-icon v-else @click="handleToggles('admin')" icon="chevron-up"></b-icon>
        </div>
        <div v-if="toggleAdmin" class="mb-4">
          <div
            class="single-online-tag"
            v-for="(item) in sortedAdminMessage()"
            :key="item.id"
            :class="{active:active==item.name}"
            @click="switchGroup(item.id,'adminChat',item)"
          >
            <div class="inner-single">
              <div class="message-info">
                <div class="avatar mr-3">
                  <b-avatar :src="item.profile"></b-avatar>
                </div>
                <div>
                  <p class="toCaps name">{{item.name}}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </b-card>
  </b-col>
</template>
<script>
export default {
  props: ["tutor", "groups", "studtutMessages", "adminMessages"],
  data() {
    return {
      active: "",
      search: "",
      id: null,
      toggleClassmates: false,
      toggleGroups: true,
      toggleTutors: false,
      toggleAdmin: false,
    };
  },
  mounted() {
    if (window.innerWidth > 900) {
      this.active = "staff";
      this.switchGroup("staff", "staff", "staff");
    }
  },

  computed: {
    sortedGroups() {
      return this.$props.groups.filter((item) =>
        item.name.toLowerCase().includes(this.search.toLowerCase())
      );
    },
    sortedNames() {
      return this.studtutMessages.filter((item) => {
        if (item.user) {
          return item.user.name
            .toLowerCase()
            .includes(this.search.toLowerCase());
        }
      });
    },
  },
  methods: {
    sortedAdminMessage() {
      var arr =[]
       var arr1 =[]
        this.$props.adminMessages.forEach(item=>{
           if (item.admin) {
              if (!arr1.includes(item.admin.id)) {
              let data = {
                id:item.admin.id,
                name:item.admin.name
              }
              arr1.push(item.admin.id)
              arr.push(data)
              
            }
           }
        })
        return arr;
    },
    handleToggles(val) {
      switch (val) {
        case "groups":
          this.toggleGroups = !this.toggleGroups;
          break;
        case "mates":
          this.toggleClassmates = !this.toggleClassmates;
          break;
        case "tutors":
          this.toggleTutors = !this.toggleTutors;
          break;
        case "admin":
          this.toggleAdmin = !this.toggleAdmin;
          break;

        default:
          break;
      }
    },
    sortedMessage() {
      var arr = [];
      var arr1 = [];
      this.sortedNames.forEach((item) => {
        if (
          item.user_id == this.$props.tutor.id ||
          item.receiver_id == this.$props.tutor.id
        ) {
          if (item.user) {
            if (!arr.includes(item.user.name)) {
              arr.push(item.user.name);
              arr1.push(item.user);
            }
          }
        }
      });
      return arr1;
    },

    sortedMessages(id) {
      var arr = [];
      var arr1 = [];
      return this.$props.studtutMessages.filter((item) => {
        return (
          (item.receiver_id == this.$props.tutor.id && item.user_id == id) ||
          (item.receiver_id == id && item.user_id == this.$props.tutor.id)
        );
      });
    },
    drop(val) {
      this.$emit("drop", val);
    },
    switchGroup(id, name, val) {
      this.active = val;
      this.$emit("switchGroup", id, name, val);
      this.id = id;
      this.$emit("toggleChat", "body");
    },
    online() {
      this.$refs.popover.$emit("close");
      this.$emit("online");
    },
    newGroup() {
      this.$refs.popover.$emit("close");
      this.$emit("newGroup");
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
.single-online-tag {
  padding: 8px 10px;
  border-bottom: 1px solid #ccc;
}
.single-online-tag.active {
  background-color: #fff;
  border-left: 3px solid #ccc;
}
.pop {
  margin: 0;
}
p {
  margin-bottom: 0.5rem;
}
.pop li {
  padding: 10px 0;
}
.online-presence {
  background: #f5f4f4;
  height: 85vh;
}
.online-presence-top {
  display: flex;
  align-items: center;
}
.online-presence-top .fa {
  padding-left: 10px;
  padding-top: 10px;
}
.inner-single {
  display: flex;
  justify-content: space-between;
}
.message-info {
  display: flex;
  /* padding-left: 20px; */
  align-items: center;
}
/* .message-info h6 {
  margin-bottom: 0;
  line-height: 0;
} */

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
label {
  margin: 0 !important;
  display: block;
}
.message {
  font-size: 14px;
  padding: 20px;
}
.chat-body {
  width: 100%;
  height: 100%;
  padding: 20px 0 60px;
  position: relative;
  overflow: auto;
}
.online {
  height: 100%;
  width: 20%;
}
.online-tag {
  max-height: 67vh;
  overflow: auto;
  padding: 16px 0;
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
strong {
  font-size: 15px;
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
  background: white;
  padding: 15px 20px;
  width: fit-content;
  width: max-content;
  max-width: 50%;

  text-align: left;
}
strong {
  font-size: 14px;
}

.name {
  font-size: 14px;
  font-weight: bold;
}
@media screen and (max-width: 500px) {
  .send-tab {
    position: fixed;
  }
}
</style>