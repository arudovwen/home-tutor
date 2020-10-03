<template>
  <div v-if="announcement.length">
    <!-- <swiper class="swiper" :options="swiperOption">
      <swiper-slide v-for="(item,idx) in announcement" :key="idx">
        <b-alert show :variant="variant[Math.round(Math.random() * 7)]" class="d-flex">
          <p class=" m-0" :class="{'w-25':show}"></p>
          <p class=" m-0 " :class="{'w-75':show}"><b-icon icon="info-circle-fill" class="mr-2"></b-icon>{{item.body}} - <span class="tiny">{{item.created_at | moment('MMM DD, YYYY')}}</span></p>
        </b-alert>
      </swiper-slide>
    </swiper> -->
    <marquee behavior="scroll" direction="left" ><div class="d-flex align-items-center justify-content-between"><p class="" v-for="(item,idx) in announcement" :key="idx">{{item.body}} - <span class="tiny">{{item.created_at | moment('MMM DD, YYYY')}}</span>  <span class="mx-4">|</span></p></div></marquee>
  </div>
</template>

<script>
export default {
  props: ["student",'show'],
  data() {
    return {
      announcement: [],
      variant: [
        "primary",
        "secondary",
        "success",
        "danger",
        "warning",
        "info",
        "light",
        "dark",
      ],
      swiperOption: {
        direction: "vertical",
        effect: "flip",
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
        autoplay: {
          delay: 7000,
          disableOnInteraction: false,
        },
      },
    };
  },
  mounted() {
    this.getA();
  },
  methods: {
    getA() {
      axios
        .get("/api/get-announcement", {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.announcement = res.data;
          }
        });
    },
  },
};
</script>

<style scoped>
.swiper {
  height: 50px;
}
.tiny{
  font-size:11px;
}
marquee{
  background-color: aliceblue;
  width:100%;
  padding:10px;
}
p{
  margin:0
}
@media (max-width: 768px) {
  .w-75{
    width:100% !important;
  }
}
</style>