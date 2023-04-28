<template>
  <div class="head-container" :style="{ background: category.theme.header }">
    <div
      class="head-content"
      :class="{ dark: checkHeaderLight(), light: checkHeaderDark() }"
    >
      <div class="head-title">
        <div
          v-if="category.logoimg == ''"
          :class="{ dark: checkHeaderLight(), light: checkHeaderDark() }"
        >
          <span>{{ title }}</span>
        </div>
        <div
          v-else
          :class="{ dark: checkHeaderLight(), light: checkHeaderDark() }"
        >
          <div class="head-title-logo-wrap">
            <img :src="category.logoimg" class="head-logo" />
            <div>{{ title }}</div>
          </div>
        </div>
        <div class="head-description">
          <p v-html="category.board.description" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import Comment from "@/components/Comment.vue";
import { getBoardIdFromUrl } from "@/helper/helper.js";
export default {
  components: {
    Comment,
  },
  props: {
    title: String,
  },
  data() {
    return {
      loading2: false,
    };
  },
  computed: {
    ...mapState(["category", "post", "comment", "user"]),
  },
  methods: {
    getBoardId() {
      return getBoardIdFromUrl();
    },
    checkHeaderLight() {
      if (
        this.checkColor(
          this.category.theme.header ? this.category.theme.header : "#1890ff"
        ) == "light"
      ) {
        return true;
      } else {
        return false;
      }
    },
    checkHeaderDark() {
      if (
        this.checkColor(
          this.category.theme.header ? this.category.theme.header : "#1890ff"
        ) == "dark"
      ) {
        return true;
      } else {
        return false;
      }
    },
    checkColor(color) {
      // Variables for red, green, blue values
      let r;
      let g;
      let b;
      let hsp;
      // Check the format of the color, HEX or RGB?
      if (color.match(/^rgb/)) {
        // If HEX --> store the red, green, blue values in separate variables
        color = color.match(
          /^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/
        );

        r = color[1];
        g = color[2];
        b = color[3];
      } else {
        // If RGB --> Convert it to HEX: http://gist.github.com/983661
        color = +(
          "0x" + color.slice(1).replace(color.length < 5 && /./g, "$&$&")
        );
        r = color >> 16;
        g = (color >> 8) & 255;
        b = color & 255;
      }
      // HSP (Highly Sensitive Poo) equation from http://alienryderflex.com/hsp.html
      hsp = Math.sqrt(0.299 * (r * r) + 0.587 * (g * g) + 0.114 * (b * b));
      // Using the HSP value, determine whether the color is light or dark 127.5
      if (hsp > 160) {
        return "light";
      } else {
        return "dark";
      }
    },
  },
};
</script>

<style scoped lang="scss">
.big-ninja-feedbo {
  .head-container {
    position: relative;
    word-break: break-word;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    padding: 25px 30px 26px;
  }
  .head-title {
    font-size: 20px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
  }

  .head-title-logo-wrap {
    display: flex;
    align-items: center;
    gap: 15px;
  }
  .head-description {
    font-size: 14px;
    font-weight: normal;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.29;
    letter-spacing: normal;
    flex: 1 1 0%;
    margin: 0.5rem 0px 0px;
  }
  .head-logo {
    height: 32px;
  }
  .dark {
    color: #000;
  }
  .light {
    color: #fff;
  }

  @media only screen and (max-width: 600px) {
    .head-container {
      border-radius: 0;
      padding: 25px 15px;
    }
  }
}
</style>
