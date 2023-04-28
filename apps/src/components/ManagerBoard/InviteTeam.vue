<template>
  <div>
    <ASpin :spinning="category.isLoadingChangeRole">
      <div class="manage-title">
        <h3 class="title-text">
          Invite Team
        </h3>
      </div>
      <AFormModel ref="emailForm" :model="form" :rules="rules">
        <AFormModelItem ref="email" prop="email">
          <div class="form-invite">
            <div class="form-invite-wrap">
              <div class="email-input">
                <AInput
                  v-model="form.email"
                  class="template-button"
                  placeholder="Enter emails invite"
                  @blur="
                    () => {
                      $refs.email.onFieldBlur();
                    }
                  "
                />
              </div>
              <div class="sent-button">
                <AButton
                  :loading="loading"
                  class="template-button"
                  type="primary"
                  :disabled="form.email == ''"
                  @click="submit"
                >
                  Invite
                </AButton>
              </div>
            </div>
          </div>
        </AFormModelItem>
      </AFormModel>
      <PerfectScrollbar class="team-list-scrollbar">
        <div class="list-users-wrap">
          <div
            v-for="(item, index) in category.userTeam"
            :key="item.ID"
            class="list-users"
          >
            <div class="list-users-item">
              <div class="user-img-wrap">
                <div class="user-img">
                  <img :src="item.user_avatar" class="avatar-img" />
                </div>
              </div>
              <div class="user-name-wrap">
                <div v-if="item.user_name == null" class="user-name">
                  {{ item.user_email }}
                  <ABadge
                    v-show="item.status == 'pending'"
                    status="processing"
                    text="Pending"
                  />
                </div>
                <div v-else class="user-name">
                  {{ category.userTeamAvatar[index].user_name }}
                  <ABadge
                    v-show="item.status == 'pending'"
                    status="processing"
                    text="Pending"
                  />
                </div>
              </div>
              <div class="user-role">
                <ADropdown placement="bottomRight" :trigger="['click']">
                  <AMenu slot="overlay" @click="handleMenuClick">
                    <AMenuItem key="Owner" :disabled="ownerDisabled">
                      <div>
                        <div>
                          <div class="role-name">
                            Owner
                          </div>
                        </div>
                        <div>
                          Can access all high-level features such as billing.
                          Only one owner possible.
                        </div>
                      </div>
                    </AMenuItem>
                    <AMenuItem key="Admin" :disabled="adminDisabled">
                      <div>
                        <div>
                          <div class="role-name">
                            Admin
                          </div>
                        </div>
                        <div>
                          Can manage feedback, add users and change all settings
                          for the board.
                        </div>
                      </div>
                    </AMenuItem>
                    <AMenuItem key="Moderator" :disabled="moderatorDisabled">
                      <div>
                        <div>
                          <div class="role-name">
                            Moderator
                          </div>
                        </div>
                        <div>
                          Can manage feedback, but cannot change any board
                          settings.
                        </div>
                      </div>
                    </AMenuItem>
                    <AMenuItem key="Member" :disabled="memberDisabled">
                      <div>
                        <div>
                          <div class="role-name">
                            Member
                          </div>
                        </div>
                        <div>
                          Can access this board. This role is only available for
                          private boards.
                        </div>
                      </div>
                    </AMenuItem>
                    <AMenuItem key="Delete" :disabled="deleteDisabled">
                      <div>
                        <div>
                          <div class="role-name">
                            Delete
                          </div>
                        </div>
                        <div>Remove user from this board.</div>
                      </div>
                    </AMenuItem>
                  </AMenu>
                  <AButton
                    class="role-button ant-btn-custom"
                    @click="showListRole(item)"
                  >
                    {{ item.term_role }} <AIcon type="down" />
                  </AButton>
                </ADropdown>
              </div>
            </div>
          </div>
        </div>
      </PerfectScrollbar>
    </ASpin>
  </div>
</template>

<script>
import { mapState } from "vuex";
import { getBoardIdFromUrl } from "@/helper/helper.js";
export default {
  computed: {
    ...mapState(["category", "user"]),
  },
  data() {
    return {
      form: {
        email: "",
      },
      rules: {
        email: [
          {
            type: "email",
            message: "Please enter a valid email address",
            trigger: "blur",
          },
        ],
      },
      loading: false,
      spinLoading: false,
      userEmail: null,
      roleCurrent: null,
      ownerDisabled: true,
      adminDisabled: true,
      moderatorDisabled: true,
      memberDisabled: true,
      deleteDisabled: false,
    };
  },
  methods: {
    submit() {
      this.$refs.emailForm.validate((valid) => {
        if (valid) {
          this.loading = true;
          const id = getBoardIdFromUrl();
          const params = {
            email: this.form.email,
            termId: id,
            component: this,
          };
          this.$store.dispatch("category/inviteTeam", params);
          setTimeout(() => {
            this.loading = false;
          }, 1000);
        } else {
          console.log("error submit!!");
          return false;
        }
      });
    },
    handleMenuClick(e) {
      this.spinLoading = true;
      const term_id = getBoardIdFromUrl();
      const params = {
        term_id: term_id,
        user_email: this.userEmail,
        user_role: e.key,
        component: this,
      };
      if (e.key == "Admin" || e.key == "Owner" || e.key == "Delete") {
        this.$store.dispatch("category/changeRole", params);
      } else {
        if (e.key == "Moderator") {
          if (this.user.currentLevel < 3) {
            if (this.userEmail == this.user.user.user_email) {
              this.$store.commit("user/SET_CURRENT_LEVEL", 3);
            }
            this.$store.dispatch("category/changeRole", params);
          } else {
            this.spinLoading = false;
          }
        } else {
          this.$store.dispatch("category/changeRole", params);
        }
      }
    },
    showListRole(params) {
      this.userEmail = params.user_email;
      if (this.user.currentLevel == 1) {
        if (params.level == 1 && this.userEmail == this.user.user.user_email) {
          this.ownerDisabled = true;
          this.adminDisabled = true;
          this.moderatorDisabled = true;
        }
        if (params.level == 1 && this.userEmail != this.user.user.user_email) {
          this.ownerDisabled = true;
          this.adminDisabled = false;
          this.moderatorDisabled = false;
        }
        if (params.level == 2) {
          this.ownerDisabled = false;
          this.adminDisabled = true;
          this.moderatorDisabled = false;
        }
        if (params.level == 3) {
          this.ownerDisabled = false;
          this.adminDisabled = false;
          this.moderatorDisabled = true;
        }
      }
      if (this.user.currentLevel == 2) {
        if (params.level == 2) {
          this.ownerDisabled = true;
          this.adminDisabled = true;
          this.moderatorDisabled = false;
        }
        if (params.level > 2) {
          this.ownerDisabled = true;
          this.adminDisabled = false;
          this.moderatorDisabled = true;
        }
        if (params.level < 2) {
          this.ownerDisabled = true;
          this.adminDisabled = true;
          this.moderatorDisabled = true;
        }
      }
      if (this.userEmail == this.user.user.user_email) {
        this.deleteDisabled = true;
      } else {
        this.deleteDisabled = false;
      }
    },
  },
};
</script>

<style lang="scss">
.big-ninja-feedbo {
  .ant-form-explain {
    margin-left: 16px;
  }
  .setting-scrollbar .ant-form-explain {
    margin-left: 0px !important;
  }
  .form-invite {
    position: relative;
    margin-bottom: 0px;
    width: 100%;
  }
  .form-invite-wrap {
    position: relative;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    opacity: 1;
    border-radius: 4px;
    padding: 0px 1rem;
  }
  .email-input {
    display: flex;
    flex: 1 1 0%;
  }
  .sent-button {
    padding-left: 5px;
    margin: -0.2rem;
  }
  .list-users-wrap {
    margin: 1rem 0px;
  }
  .list-users {
    position: relative;
    min-width: 0px;
    border-radius: 4px;
    padding: 0px 1rem;
  }
  .list-users-item {
    background-color: #fff;
    border: 1px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    margin-bottom: 0.5rem;
    min-height: 2.75rem;
    width: 100%;
    font-size: 0.9rem;
    opacity: 1;
    padding: 0.75rem 1rem;
  }
  .user-img {
    position: relative;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    height: 32px;
    width: 32px;
    border-radius: 50%;
    overflow: hidden;
  }
  .avatar-img {
    height: 32px;
    width: 32px;
    opacity: 1;
  }
  .user-name-wrap {
    min-width: 0px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    flex: 1 1 0%;
    overflow: hidden;
  }
  .user-name {
    text-overflow: ellipsis;
    white-space: nowrap;
    margin: 0px 1rem;
    overflow: hidden;
  }
  .user-role {
    font-size: 0.8rem;
    line-height: 1rem;
    color: rgba(207, 209, 225, 0.713);
    margin: 0px 0.25rem 0px 1rem;
  }
  .role-button {
    background-color: var(--main-color) + "00" !important;
    border: none !important;
    box-shadow: none;
    margin-left: 8px;
  }
  .role-button:hover {
    background-color: var(--main-color) + "00" !important;
  }
  .role-name {
    font-weight: bold;
  }
  .team-list-scrollbar {
    height: 400px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
    border-bottom-left-radius: 8px;
  }
}
</style>
