<template>
  <div>
    <div class="board-manager-wrap">
      <AButton
        :type="checkTheme()"
        class="template-button"
        :class="{ 'button-manager': checkTheme() == '' }"
        @click="openBoard"
      >
        Manage Board
      </AButton>
    </div>
    <ADrawer
      title="Manager Board"
      :visible="boardVisible"
      :width="900"
      placement="left"
      @close="closeBoard"
    >
      <ARow>
        <ACol :span="5" class="menu-left">
          <AMenu
            style="width: 188px"
            :default-selected-keys="['dashboard']"
            mode="inline"
            :selected-keys="[current]"
            @click="handleClick"
          >
            <AMenuItem key="dashboard" class="manager-menu-item">
              <AIcon type="dashboard" />
              Dashboard
            </AMenuItem>
            <AMenuItem key="inviteteam" class="manager-menu-item">
              <AIcon type="usergroup-add" />
              Invite Team
            </AMenuItem>
            <AMenuItem key="setting" class="manager-menu-item">
              <AIcon type="setting" />
              Settings
            </AMenuItem>
            <AMenuItem key="appearance" class="manager-menu-item">
              <AIcon type="bg-colors" />
              Appearance
            </AMenuItem>
            <AMenuItem key="dangerzone" class="manager-menu-item">
              <AIcon type="warning" />
              Danger zone
            </AMenuItem>
            <AMenuItem key="widget" class="manager-menu-item">
              <AIcon type="tool" />
              Widget
            </AMenuItem>
          </AMenu>
        </ACol>
        <ACol :span="19" class="menu-right">
          <DashBoard v-if="current == 'dashboard'" class="menu-right-wrap" />
          <InviteTeam
            v-if="current == 'inviteteam'"
            class="menu-right-wrap"
            @closeBoardManager="closeBoard"
          />
          <Setting v-if="current == 'setting'" class="menu-right-wrap" />
          <Appearance v-if="current == 'appearance'" class="menu-right-wrap" />
          <DangerZone v-if="current == 'dangerzone'" class="menu-right-wrap" />
          <Widget v-if="current == 'widget'" class="menu-right-wrap" />
        </ACol>
      </ARow>
    </ADrawer>
  </div>
</template>

<script>
import { mapState } from "vuex";
import DashBoard from "@/components/ManagerBoard/DashBoard.vue";
import InviteTeam from "@/components/ManagerBoard/InviteTeam.vue";
import Appearance from "@/components/ManagerBoard/Appearance.vue";
import Setting from "@/components/ManagerBoard/Setting.vue";
import DangerZone from "@/components/ManagerBoard/DangerZone.vue";
import Widget from "@/components/ManagerBoard/Widget.vue";
export default {
  components: {
    DashBoard,
    InviteTeam,
    Appearance,
    Setting,
    DangerZone,
    Widget,
  },
  data() {
    return {
      current: "dashboard",
      boardVisible: false,
      mainColor: "",
    };
  },
  computed: {
    ...mapState(["category"]),
  },
  methods: {
    openBoard() {
      this.boardVisible = true;
    },
    handleClick(e) {
      this.current = e.key;
    },
    closeBoard() {
      this.boardVisible = false;
    },
    checkTheme() {
      if (this.category.theme.accent == "#1890ff") {
        return "primary";
      } else {
        if (this.category.theme.accent == "#ff4d4f") {
          return "danger";
        } else {
          return "";
        }
      }
    },
  },
};
</script>

<style lang="scss">
.big-ninja-feedbo {
  a {
    text-decoration: none;
    cursor: pointer;
    color: inherit;
  }
  .board-manager-wrap {
    position: fixed;
    bottom: 30px;
    left: 30px;
    z-index: 99;
  }
  .menu-left {
    background: white;
    height: 100vh;
  }
  .menu-right {
    margin-top: 20px;
    position: relative;
    flex: 4 1 0%;
    overflow: hidden;
  }
  .menu-right-wrap {
    width: 100%;
    height: 90vh;
    padding: 0rem 3rem 2rem;
  }
  .button-manager {
    color: white !important;
    background-color: var(--main-color) !important;
    border-color: var(--main-color) !important;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.12) !important;
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.045) !important;
  }
  .button-manager:hover {
    filter: brightness(1.15);
    border-color: var(--main-color) !important;
  }
  .ant-drawer-content {
    overflow: hidden !important;
  }
  .ant-drawer-wrapper-body {
    background: #f0f2f5;
    overflow: hidden !important;
  }
  .ant-drawer-body {
    padding: 0 0 80px 0 !important;
  }
  .ps__rail-y {
    border-radius: 8px;
  }
  .ps__rail-x {
    border-radius: 8px;
  }
  .manage-title {
    -webkit-box-align: center;
    margin-bottom: 2rem;
    margin-top: 10px;
  }
  .title-text {
    color: #000;
    font-size: 1.1rem;
  }
  @media only screen and (max-width: 600px) {
    .ant-drawer-content-wrapper {
      width: 100vw !important;
    }
    .ant-menu {
      width: 40px !important;
    }
    .ant-col-5 {
      width: 10% !important;
    }
    .ant-col-19 {
      width: 90% !important;
    }
    .menu-right-wrap {
      padding: 0 1rem 2rem;
    }
    .upload-container {
      grid-template-columns: 1fr;
    }
    .board-manager-wrap {
      bottom: 60px;
      left: 0px;
    }
    .manager-menu-item {
      padding-left: 10px !important;
    }
  }
}
</style>
