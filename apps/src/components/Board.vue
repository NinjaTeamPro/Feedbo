<template>
  <div class="board" :style="{ backgroundColor: category.theme.background }">
    <div class="board-home-link">
      <router-link :to="{ name: 'Home' }" class="FeedboProject">
        <img :src="logoLink" alt="Feedbo Logo" class="feedbo-main-logo" />
        <span class="Feedbo">
          Feedbo
        </span>
      </router-link>
    </div>
    <ASpin :spinning="board.isLoadingBoard">
      <div
        class="board-content"
        :style="[
          post.posts != null && post.posts.length >= 6
            ? { paddingBottom: '0' }
            : { paddingBottom: '10%' },
        ]"
      >
        <Header :title="category.board.name" class="board-header" />
        <div class="board-main">
          <Menu class="board-menu" />
          <ListPost class="board-list-post" />
        </div>
      </div>
    </ASpin>
    <NavBar class="board-navbar" @changeBoard="changeBoard()" />
    <BoardManage v-if="user.currentLevel < 3" />
  </div>
</template>

<script>
import Menu from "@/components/Menu.vue";
import ListPost from "@/components/ListPost.vue";
import NavBar from "@/components/NavBar.vue";
import Header from "@/components/Header.vue";
import BoardManage from "@/components/ManagerBoard/BoardManage.vue";
import { mapState } from "vuex";
export default {
  components: {
    Header,
    Menu,
    ListPost,
    NavBar,
    BoardManage,
  },
  data() {
    return {
      logoLink:
        window.bigNinjaVoteWpdata.pluginUrl +
        "assets/img/feedbo-logo-square.png",
    };
  },
  computed: {
    ...mapState(["board", "category", "user", "post"]),
  },
  mounted() {
    const component = this;
    const boardId = this.$route.params.id;
    this.$store.dispatch("board/getBoardContent", { boardId, component });
  },
  methods: {
    checkVisibility() {
      let check = false;
      if (this.category.board.visibility == "public") {
        check = true;
      } else {
        if (this.user.userAnonymous == false) {
          const currentUserName = this.user.user.user_email;
          this.category.userTeam.forEach((item) => {
            if (currentUserName == item.user_email) {
              check = true;
            }
          });
        }
      }
      return check;
    },
    renderBoard() {
      if (this.checkVisibility() == false) {
        this.$router.push({ path: `/private/` });
      } else {
        document.title =
          this.category.board.name +
          "  | " +
          window.bigNinjaVoteWpdata.siteName;
        if (
          this.category.faviconimg != "" &&
          this.category.faviconimg != null
        ) {
          // eslint-disable-next-line no-var
          var link =
            document.querySelector("link[rel*='icon']") ||
            document.createElement("link");
          link.type = "image/x-icon";
          link.rel = "shortcut icon";
          link.href = this.category.faviconimg;
          document.getElementsByTagName("head")[0].appendChild(link);
        } else {
          const link =
            document.querySelector("link[rel*='icon']") ||
            document.createElement("link");
          link.type = "image/x-icon";
          link.rel = "shortcut icon";
          link.href =
            window.bigNinjaVoteWpdata.pluginUrl +
            "assets/img/feedbo-logo-square.png";
          document.getElementsByTagName("head")[0].appendChild(link);
        }
        const hoverColor = this.category.theme.accent + "99";
        document.documentElement.style.setProperty(
          "--main-color",
          this.category.theme.accent
        );
        document.documentElement.style.setProperty("--hover-color", hoverColor);
        if (this.mobileCheck()) {
          document.body.style.backgroundColor = "#fff";
        } else {
          document.body.style.backgroundColor = this.category.theme.background;
        }
      }
    },
    mobileCheck() {
      let check = false;
      (function(a) {
        if (
          /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(
            a
          ) ||
          /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
            a.substr(0, 4)
          )
        ) {
          check = true;
        }
      })(navigator.userAgent || navigator.vendor || window.opera);
      if (check === false) {
        const waWidth =
          window.innerWidth > 0 ? window.innerWidth : screen.width;
        if (waWidth < 500) {
          check = true;
        }
      }
      return check;
    },
  },
};
</script>

<style scoped lang="scss">
.big-ninja-feedbo {
  * {
    box-sizing: border-box;
    margin: 0px;
    padding: 0px;
  }
  a {
    cursor: pointer;
    text-decoration: none;
  }
  .FeedboProject {
    display: flex;
    align-items: center;
    height: 37px;
    font-size: 24px;
    font-weight: bold;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #1a2a37;
  }
  .Feedbo {
    padding-left: 10px;
  }
  .board-home-link {
    display: flex;
    letter-spacing: 1px;
    padding: 20px;
  }

  .board-home-link a:hover {
    text-decoration: none;
    color: inherit;
  }
  .board-content {
    width: 650px;
    margin: 0 auto;
    padding-top: 5%;
  }

  .board-menu {
    background-color: #ffffff;
  }

  .board-list-post {
    background-color: #ffffff;
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
    max-height: 650px;
    overflow-x: hidden;
    overflow-y: auto;
  }
  .ant-btn-custom {
    box-shadow: none !important;
  }
  .board {
    height: 100%;
  }
  .ant-tabs-nav-wrap {
    padding-left: 20px;
  }
  .template-button {
    height: 35px !important;
  }
  .template-input {
    height: 50px !important;
  }
  .feedbo-main-logo {
    height: 50px;
    width: 50px;
  }
  @media only screen and (min-width: 768px) {
    .board-main {
      margin-top: 0 !important;
    }
  }
  @media only screen and (max-width: 600px) {
    .feedbo-main-logo {
      height: 42px;
      width: 42px;
    }
    .FeedboProject {
      font-size: 22px;
    }
    .board-content {
      width: 100%;
      padding-top: 0;
    }
    .board-home-link {
      display: flex;
      letter-spacing: 1px;
      padding-left: 0;
      padding: 20px 15px;
    }

    .board-main {
      position: relative;
      top: 0;
      left: 0;
      width: 100%;
      transition: all 0.5s ease-out;
      z-index: 99;
    }

    .board-list-post {
      max-height: calc(100dvh - 56px);
    }
  }
}
</style>
