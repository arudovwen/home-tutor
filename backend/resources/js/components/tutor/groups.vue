<template>
  <div class="p-0 p-sm-4">
    <Chat
      :tutor="tutor"
      :onlineGroupMembers="onlineGroupMembers"
      :groupMessages="groupMessages"
      @addGroupMessage="addGroupMessage"
      :groups="groups"
      :group_id="group_id"
      @switchGroup="switchGroup"
      :onlineStaffs="onlineStaffs"
      :staffsMessages="staffsMessages"
      @addStaffMessage="addStaffMessage"
      :showChat="showChat"
      @newGroup="newGroup"
       :receiver_id="receiver_id"
      @drop="drop"
        @addStudTutMessage="addStudTutMessage"
      :studtutMessages="studtutMessages"
     
    />
    <!-- <staffChat   :tutor="tutor"  :onlineStaffs="onlineStaffs"
      :staffsMessages="staffsMessages"    @addStaffMessage="addStaffMessage"/>
    -->

    <b-modal id="group" title="New Group" hide-footer>
      <form @submit.prevent="addClass" class="mb-5">
        <div class="form-group">
          <label for>Group name</label>
          <input
            type="text"
            class="form-control"
            v-model="name"
            aria-describedby="helpId"
            placeholder="Pearl"
          />
        </div>
        <div class="mt-3 text-center">
          <b-button-group size="sm">
            <b-button @click="switchGrouping(true)" variant="info">Class Group</b-button>

            <b-button @click="switchGrouping(false)">Student Group</b-button>
          </b-button-group>
        </div>

        <div class="d-flex mt-4">
          <div class="form-group" v-if="group_type">
            <label for>Add Entire Class</label>
            <select class="custom-select" v-model="class_name">
              <option selected value>Select Class</option>
              <option :value="item" v-for="(item,idx) in allClass" :key="idx">{{item}}</option>
            </select>
          </div>

          <div v-else>
            <div class="form-group">
              <label for>Add Students</label>
              <select class="custom-select" v-model="name_class">
                <option selected value>Select Class</option>
                <option :value="item" v-for="(item,idx) in allClass" :key="idx">{{item}}</option>
              </select>
            </div>

            <div class="names form-group p-3" v-if="names.length">
              <label class="custom-control custom-checkbox" v-for="(item,idx) in names" :key="idx">
                <input
                  type="checkbox"
                  :value="item.id"
                  class="custom-control-input"
                  v-model="added_names"
                />
                <span class="custom-control-indicator">{{item.name}}</span>
              </label>
            </div>
          </div>
        </div>

        <div class="form-group my-4">
          <b-button v-if="!update" type="submit" size="sm" class="btn btn-primary">Create</b-button>
          <div v-else class="d-flex justify-content-between">
            <b-button type="button" size="sm" class="btn btn-primary" @click="updateN">Update</b-button>
            <b-button type="button" size="sm" class="btn btn-primary" @click="cancel">Cancel</b-button>
          </div>
        </div>
      </form>
    </b-modal>
  </div>
</template>


<script>
import Chat from "./chat";
import StaffChat from "./staffChat";
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
      group_type: true,
      heads: [],
      name: "",
      class_name: "",
      items: [],
      item: false,
      update: false,
      allClass: [],
      name_class: "",
      names: [],
      added_names: [],
    };
  },
  watch: {
    item: "selectAll",
    name_class: "getStudents",
    class_name: "reset",
  },
  components: {
    Chat,
    StaffChat,
  },
  mounted() {
    // this.getgroups();
    this.getCLasses();
  },
  methods: {
     addStudTutMessage(message, attachment) {
      this.$emit("addStudTutMessage", message, attachment);
    },
    switchGrouping(val) {
      this.group_type = val;
    },
    newGroup() {
      this.$bvModal.show("group");
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
    reset() {
      this.names = [];
      this.added_names = [];
      this.name_class = "";
    },
    cancel() {
      this.update = false;

      this.name = "";
    },
    selectAll() {
      if (this.item) {
        this.items = [];
        this.groups.forEach((it) => {
          this.items.push(it.id);
        });
      } else {
        this.items = [];
      }
    },
    getCLasses() {
      axios
        .get("/api/all-classes", {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            res.data.forEach((item) => {
              this.allClass.push(item.class_name);
              if (item.sub_class !== "") {
                item.sub_class.split(",").forEach((i) => {
                  this.allClass.push(i);
                });
              }
            });
          }
        });
    },
    getStudents() {
      if (this.name_class !== "") {
        axios
          .get(`/api/all-students/${this.name_class}`, {
            headers: {
              Authorization: `Bearer ${this.$props.tutor.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.added_names = [];
              this.names = res.data;
            }
          });
      }
    },
    addClass() {
      if (this.added_names.length) {
        var data = {
          name: this.name.replace(/ /g,'-'),
          class_name: this.name_class,
          subscribers: this.added_names,
        };
      } else {
        var data = {
          name: this.name,
          class_name: this.class_name,
          subscribers: null,
        };
      }

      axios
        .post("/api/group", data, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.name = "";
            this.class_name = "";
            this.getgroups();
          }
        });
    },
    updateN() {
      axios
        .put(`/api/group/${this.data.id}`, this.data, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.getgroups();
            this.update = false;
            this.name = "";
          }
        });
    },
    gotoGroup(id) {
      this.$router.push(`/tutor/group/${id}`);
    },
    edit(id) {
      axios
        .get(`/api/group/${id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.name = res.data.name;
            this.update = true;
          }
        });
    },

    drop(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/group/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.tutor.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
            this.$emit('updateChat')
            }
          });
      }
    },
    multiDrop() {
      let del = confirm("Are you sure about this?");
      let data = {
        data: this.items,
      };
      if (del) {
        axios
          .post("/api/multi-group-drop", data, {
            headers: {
              Authorization: `Bearer ${this.$props.tutor.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getgroups();
              this.item = false;
            }
          })
          .catch((err) => {
            console.log("del -> err", err);
          });
      }
    },
  },
};
</script>

<style scoped>
nav {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr;
  grid-column-gap: 30px;
}
.names {
  max-height: 400px;
  overflow: hidden;
  background: white;
}
.custom-control-input {
  z-index: 1;
  opacity: 1;
}
td {
  text-transform: capitalize;
  position: relative;
}
.chat {
  cursor: pointer;
}
.enter {
  position: absolute;
  right: 20px;
  top: 50%;
  margin-top: -10px;
}
.hiden {
  opacity: 0;
}

.add {
  background-color: #f7f8fa;
}
.body {
  padding: 20px 20px 50px;
  height: 100%;
}
table {
  font-size: 14.5px;
}

</style>