<template>
  <div>
    <ADropdown v-if="user.userAnonymous == false" :trigger="['click']">
      <div
        v-if="user.userAnonymous == false"
        class="account"
        @click="showModal2"
      >
        <div class="img-account-container">
          <div class="img-account-wrap">
            <div class="img-account-flex">
              <img :src="user.user.user_avatar" class="img-account" />
            </div>
          </div>
        </div>
      </div>
      <AMenu slot="overlay" class="account-popup">
        <AMenuItem key="0">
          <div class="account-info" @click="showSettingAccountModal">
            <img :src="user.user.user_avatar" class="img-account model" />
            <div class="account-text account-name">
              {{ user.user.display_name }}
            </div>
            <div class="Account-Settings">Account settings</div>
          </div>
        </AMenuItem>
        <AMenuDivider />
        <AMenuItem key="1">
          <div class="link-new-board">
            <router-link :to="{ name: 'NewBoard' }">
              <AIcon class="icon-plus" type="plus" />
              <div class="Create-New-Board" @click="changeNewBoard()">
                Create New Board
              </div>
            </router-link>
          </div>
          <PerfectScrollbar class="list-board-scrollbar">
            <div
              v-for="item in user.user.list_board"
              :key="item.term_id"
              class="list-board"
            >
              <router-link
                :to="{ name: 'Board', params: { id: item.board_URL } }"
              >
                <div class="icon-oval" />
                <span
                  class="Title-Board"
                  @click="changeCategory(item.board_URL)"
                >
                  {{ item.name }}
                </span>
                <AIcon
                  v-if="checkActiveBoard(item.board_URL)"
                  type="check"
                  class="check-icon"
                />
              </router-link>
            </div>
          </PerfectScrollbar>
        </AMenuItem>
      </AMenu>
    </ADropdown>
    <div v-if="user.userAnonymous == true" class="account">
      <a-button @click="signIn">Sign in</a-button>
    </div>
    <!-- Modal setting account -->
    <AModal
      id="modal-account-settings"
      centered
      title="Account settings"
      :visible="visibleSetting"
      :footer="false"
      @cancel="handleCancel3"
    >
      <div class="account-setting-item" @click="showEditProfile">
        <a class="account-setting-item-text">
          Edit Profile
        </a>
        <AIcon class="icon-right" type="right" />
      </div>
      <div class="account-setting-item" @click="showNotificationSetting">
        <a class="account-setting-item-text">
          Notification Settings
        </a>
        <AIcon class="icon-right" type="right" />
      </div>
      <a :href="logoutLink">
        <div class="account-setting-item">
          <a :href="logoutLink" class="account-setting-item-text"> Logout </a>
        </div>
      </a>
    </AModal>
    <!-- Modal Edit Profile -->
    <AModal
      :visible="visibleAccount"
      centered
      :footer="false"
      :closable="false"
      @cancel="handleCancel4"
    >
      <div slot="title" class="edit-profile-title" @click="handleCancel4">
        <div class="edit-profile-icon">
          <AIcon type="arrow-left" />
        </div>
        <div>Edit Profile</div>
      </div>
      <Account />
    </AModal>
    <!-- Modal Notification Settings -->
    <AModal
      :visible="visibleNotification"
      centered
      :footer="false"
      :closable="false"
      @cancel="handleCancel5"
    >
      <div slot="title" class="edit-profile-title" @click="handleCancel5">
        <div class="edit-profile-icon">
          <AIcon type="arrow-left" />
        </div>
        <div>Notification Settings</div>
      </div>
      <Notification />
    </AModal>
  </div>
</template>

<script>
import { mapState } from "vuex";
import Account from "@/components/Account/Account.vue";
import Notification from "@/components/Account/Notification.vue";
import { setCookie } from "@/helper/helper";
export default {
  components: {
    Account,
    Notification,
  },
  data() {
    return {
      spinLoading: false,
      visibleAvatar: false,
      visibleSetting: false,
      visibleAccount: false,
      visibleNotification: false,
      modalVisible: false,
      logoutLink: "",
    };
  },
  mounted() {
    if (this.$route.name == "Home" || this.$route.name == "NewBoard") {
      this.$store.dispatch("user/fetchUser");
    }
  },
  methods: {
    showSettingAccountModal() {
      this.visibleAvatar = false;
      this.visibleSetting = true;
    },
    showEditProfile() {
      this.visibleSetting = false;
      this.visibleAccount = true;
    },
    showNotificationSetting() {
      this.visibleSetting = false;
      this.visibleNotification = true;
    },
    handleCancel3(e) {
      this.visibleSetting = false;
    },
    handleCancel4(e) {
      this.visibleAccount = false;
      this.visibleSetting = true;
    },
    handleCancel5(e) {
      this.visibleNotification = false;
      this.visibleSetting = true;
    },

    signIn() {
      const currentUrl = this.getCurrentUrl();
      setCookie('feedbo_previous_url', currentUrl, 1).then(() => {
        window.location.href =
          window.bigNinjaVoteWpdata.siteUrl + "/wp-login.php";
      });
    },
    getCurrentUrl() {
      return window.location.href;
    },
    checkActiveBoard(board_URL) {
      if (
        window.location.href ===
        window.bigNinjaVoteWpdata.siteUrl + "/#/board/" + board_URL
      ) {
        return true;
      } else {
        return false;
      }
    },
    changeNewBoard() {
      this.visibleAvatar = false;
    },
    showModal2() {
      this.spinLoading = true;
      this.logoutLink = window.bigNinjaVoteWpdata.logoutUrl;
      setTimeout(() => {
        this.visibleAvatar = true;
        this.spinLoading = false;
      }, 1000);
    },
    changeCategory(id) {
      this.visibleAvatar = false;
      this.category.faviconUpdate = "";
      this.category.logoUpdate = "";
      if (this.$route.name == "Board") {
        let component = this;
        let boardId = id;
        this.$store.dispatch("board/getPosts", { boardId, component });
        this.$store.dispatch("board/getBoardContent", { boardId, component });
      }
    },
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
          this.category.board.name + " | " + window.bigNinjaVoteWpdata.siteName;
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
          var link =
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
        document.body.style.backgroundColor = this.category.theme.background;
      }
    },
    fetchData() {
      let res = [];
      let maxLength = this.post.posts.length;
      let loadIndex =
        this.post.postIndexLoad + 10 > maxLength
          ? maxLength
          : this.post.postIndexLoad + 10;
      for (let index = this.post.postIndexLoad; index < loadIndex; index++) {
        res.push(this.post.posts[index]);
      }
      setTimeout(() => {
        this.$store.commit("post/UPDATE_LIST_POST", res);
        this.$store.commit("post/SET_GENERNAL", { postIndexLoad: loadIndex });
        this.loading = false;
      }, 1000);
    },
  },
  computed: {
    ...mapState(["category", "post", "comment", "user"]),
  },
};
</script>

<style lang="scss">
.big-ninja-feedbo {
  .img-account-wrap {
    position: relative;
    cursor: inherit;
    width: 40px;
    height: 40px;
  }
  .account-popup {
    width: 100%;
    background-color: #ffffff;
    .account-popup-divider {
      margin: 4px 13px;
    }
    li:hover:not(.ant-dropdown-menu-item-divider) {
      background-color: #fff;
    }
  }
  .account-info {
    margin-top: 10px;
    width: 415px;
    height: 80px;
    background-color: #f8fafb;
    cursor: pointer;
  }
  .img-account {
    height: 40px;
    width: 40px;
    border: solid 1px #ffffff;
    border-radius: 50%;
  }
  .account-text {
    margin-left: 85px;
    height: 17px;
  }
  .account-name {
    padding-top: 21px;
    font-size: 14px;
    font-weight: normal;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #74788d;
  }
  .model {
    position: absolute;
    margin-top: 20px;
    margin-left: 30px;
  }
  .link-new-board {
    height: 50px;
    width: 100%;
    padding: 20px 0 20px 27px;
  }
  .Create-New-Board {
    font-size: 14px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #1890ff;
    display: inline;
  }
  .icon-plus {
    width: 10px;
    height: 10px;
    color: #1890ff;
    margin-right: 9px;
  }
  .icon-oval {
    width: 8px;
    height: 8px;
    border: solid 1px #1a2a37;
    margin-right: 9px;
    border-radius: 50%;
    display: inline-flex;
  }
  .check-icon {
    width: 8px;
    height: 7px;
    color: #1a2a37;
    position: absolute;
    right: 30px;
    padding-top: 5px;
  }
  .list-board-scrollbar {
    max-height: 300px;
  }
  .list-board {
    height: 50px;
    width: 100%;
    padding-left: 30px;
    padding-top: 15px;
    a {
      color: #1a2a37;
      text-decoration: none;
    }
  }
  .account {
    margin-left: 0.75rem;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    outline: none;
    cursor: pointer;
  }
  .account-setting-item {
    cursor: pointer;
    padding-top: 17px;
    padding-left: 24px;
    height: 50px;
    background-color: #ffffff;
    font-weight: normal;
  }
  .account-setting-item:hover {
    background-color: #f8fafb;
    font-weight: 500;
  }
  .account-setting-item:hover a {
    color: #1890ff;
  }
  .account-setting-item-text {
    color: #1a2a37;
    height: 17px;
    font-size: 14px;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
  }
  .icon-right {
    width: 6px;
    height: 10px;
    color: #1a2a37;
    position: absolute;
    right: 30px;
    padding-top: 5px;
  }
  .edit-profile-icon {
    display: inline-block;
    float: left;
    cursor: pointer;
  }
  .edit-profile-title {
    text-align: center;
  }
  .img-account-flex {
    position: relative;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    height: 40px;
    width: 40px;
    border-radius: 50%;
    overflow: hidden;
  }
  .Account-Settings {
    padding-top: 15px;
    margin-left: 85px;
    height: 19px;
    font-size: 16px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #1a2a37;
  }
  @media only screen and (max-width: 600px) {
    .account-info {
      width: 300px;
    }
  }
}
</style>
