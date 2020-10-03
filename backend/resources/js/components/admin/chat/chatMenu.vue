<template>
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
      class="mb-4 d-flex justify-content-between align-items-center"
      @click="handleToggles('tutors')"
    >
      <strong class="text-muted">
        <b-avatar text="T" class="mr-2" size="1.3rem"></b-avatar>
        {{JSON.parse(admin.level).includes('tertiary')?'Lecturers ':'Teachers'}}
      </strong>
      <b-icon icon="chevron-down" v-if="!toggleTutors"></b-icon>
      <b-icon v-else @click="handleToggles('tutors')" icon="chevron-up"></b-icon>
    </div>
    <div class="mb-4" v-if="toggleTutors">
      <div
        class="single-online-tag"
        v-for="(item) in sortedTutor"
        :key="item.id"
        :class="{active:active==item.name}"
        @click="switchName(item.id,item)"
      >
        <div class="inner-single">
          <div class="message-info">
            <div class="avatar mr-3">
              <b-avatar :src="item.profile"></b-avatar>
            </div>
            <div>
              <p class="toCaps name">{{item.name}}</p>
             
              <small
                v-if="sortedMessage(item.id).length"
              >{{sortedMessage(item.id)[sortedMessage(item.id).length-1].message}}</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </b-card>
</template>
<script>
export default {
  props: ["groupMessages"],
  data() {
    return {
      active: "",
      search: "",
      id: null,
      toggleClassmates: false,
      toggleGroups: true,
      toggleTutors: true,
      tutors: [],
      admin: {},
    };
  },
  created() {
    this.admin = JSON.parse(localStorage.getItem("typeAdmin"));
  },
  mounted() {
    this.getAdmins();
  },

  computed: {
    sortedTutor() {
      return this.tutors.filter((item) =>
        item.name.toLowerCase().includes(this.search.toLowerCase())
      );
    },
  },
  methods: {
    sortedMessage(id) {
      return this.$props.groupMessages.filter(
        (item) =>
          item.tutor_id == id && Number(item.admin_id) == Number(this.admin.id)
      );
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

        default:
          break;
      }
    },
    getAdmins() {
      axios
        .get("/api/tutor", {
          headers: {
            Authorization: `Bearer ${this.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.tutors = res.data;
            if (window.innerWidth > 900) {
              // if (this.$route.query) {
              //   this.switchName(this.$route.query.id, this.$route.query.name);
              //   this.active = this.$route.query.name;
              // } else {
                this.switchName(res.data[0].id, res.data[0]);
                this.active = res.data[0].name;
              // }
            }
          }
        });
    },

    drop(val) {
      this.$emit("drop", val);
    },
    switchName(id, name) {
      this.active = name.name;
      console.log("switchName -> name.name", name)
      this.$emit("switchName", id, name);
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