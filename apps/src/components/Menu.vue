<template>
  <div v-if="!mobileDisplay">
    <ATabs :active-key="defaultTab" @change="setActiveTab">
      <ATabPane key="roadmap">
        <span slot="tab">
          <AIcon type="control" />
        </span>
      </ATabPane>
      <ATabPane key="vote" tab="Most Voted" />
      <ATabPane key="new" tab="Newest" />
      <div slot="tabBarExtraContent" class="tab-extra">
        <NewPost />
      </div>
    </ATabs>
  </div>
  <div v-else class="mobile-head-menu">
    <div>
      <ASelect
        :value="defaultTab"
        style="width: 120px"
        class="mobile-head-menu-select"
        @change="setActiveTab"
      >
        <ASelectOption value="roadmap">
          Roadmap
        </ASelectOption>
        <ASelectOption value="vote">
          Most Voted
        </ASelectOption>
        <ASelectOption value="new">
          Newest
        </ASelectOption>
      </ASelect>
    </div>
    <NewPost />
  </div>
</template>

<script>
const formItemLayout = {
  labelCol: { span: 4 },
  wrapperCol: { span: 8 },
};
const formTailLayout = {
  labelCol: { span: 4 },
  wrapperCol: { span: 8, offset: 4 },
};
import NewPost from "@/components/NewPost.vue";
import { getBoardIdFromUrl } from "@/helper/helper.js";
import { mapState } from "vuex";
export default {
  components: {
    NewPost,
  },
  data() {
    return {
      loading: false,
      loading1: false,
      modalVisible: false,
      id: null,
      skeletonLoading: false,
      loadingCreated: false,
      visible: false,
      formItemLayout: "vertical",
      formTailLayout,
      form: this.$form.createForm(this, { name: "dynamic_rule" }),
      postItem: null,
      linkUpload: "",
      mobileDisplay: false,
    };
  },
  computed: {
    ...mapState(["category", "comment", "post", "user"]),
    group() {
      const item = this.post.status;
      const group = item.reduce((r, a) => {
        r[a.post_content_filtered] = [...(r[a.post_content_filtered] || []), a];
        return r;
      }, {});
      return group;
    },
    defaultTab() {
      return this.post.tabActive;
    },
  },
  created() {
    window.addEventListener("load", this.mobileCheck);
    window.addEventListener("resize", this.mobileCheck);
  },
  destroyed() {
    window.removeEventListener("load", this.mobileCheck);
    window.removeEventListener("resize", this.mobileCheck);
  },
  mounted() {
    this.linkUpload = window.bigNinjaVoteWpdata.axiosUrl + "/v1/wp_upload_file";
  },
  methods: {
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
    checkRoadMap() {
      const str =
        this.category.board.length > 0 ? this.category.board.features : "";
      return str.includes("roadmap");
    },
    showModalPost(item) {
      this.postItem = item;
      this.skeletonLoading = true;
      this.modalVisible = true;
      this.$store.dispatch("comment/fetchComments", item.ID);
      this.$store.dispatch("comment/fetchUsersLike", item.ID);
      this.$store.dispatch("comment/fetchCommentImages", item.ID);
      this.$store.dispatch("post/getPostByID", item.ID);
      this.$store.dispatch("post/fetchUserSubscribe", item.post_id);
      this.$store.dispatch("post/listVoter", item.meta_value);
      setTimeout(() => {
        this.skeletonLoading = false;
      }, 800);
    },
    setActiveTab(key) {
      const id = getBoardIdFromUrl();
      this.$store.dispatch("post/changeTabActive", { key, id });
    },
    showModal() {
      this.visible = true;
    },
    handleOk(id) {
      this.form.validateFields((err, values) => {
        if (!err) {
          this.$store.dispatch("post/createPost", { values, id });
          this.loadingCreated = true;
          setTimeout(() => {
            this.$store.dispatch("post/fetchPosts", id);
            this.visible = false;
            this.loadingCreated = false;
            this.$message.success("Add new post success");
          }, 800);
        }
      });
    },
    handleCancel(e) {
      this.visible = false;
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
      this.mobileDisplay = check;
    },
  },
};
</script>

<style lang="scss">
.big-ninja-feedbo {
  .ant-tabs-bar {
    padding-left: 20px;
  }
  .ant-tabs-tab {
    color: #251016 !important;
    height: 65px !important;
    padding: 20px 16px !important;
  }
  .ant-tabs-tab:hover {
    opacity: 0.8;
  }
  .ant-tabs-tab-active:hover {
    opacity: 1 !important;
  }
  .ant-tabs-ink-bar {
    background-color: #251016;
  }
  .ant-tabs-tab-active {
    font-weight: 500;
    text-shadow: none !important;
  }
  .menu-tabs {
    padding-left: 30px;
  }
  .tab-extra {
    margin-right: 30px;
    margin-top: 10px;
  }
  .template-button {
    height: 35px !important;
  }

  .feedbo-input:hover,
  .feedbo-input.ant-mentions:hover,
  .search-input .ant-input:hover,
  .mobile-head-menu-select .ant-select-selection:hover,
  .mobile-head-menu-select .ant-select-selection--single:hover,
  .ant-input-affix-wrapper:hover .ant-input:not(.ant-input-disabled) {
    border-color: #494949;
  }
  .feedbo-input.ant-input:focus,
  .feedbo-input.ant-mentions:focus,
  .feedbo-input.ant-mentions-focused,
  .search-input .ant-input:focus,
  .mobile-head-menu-select .ant-select-selection:focus,
  .mobile-head-menu-select .ant-select-selection--single:focus,
  .mobile-head-menu-select .ant-select-open .ant-select-selection:active,
  .ant-input-affix-wrapper:focus .ant-input:not(.ant-input-disabled) {
    border-color: #494949;
    box-shadow: 0 0 0 2px rgba(206, 224, 240, 0.2);
  }
  .status-button:hover,
  .button-attach:hover,
  .feedbo-simple-button:hover,
  .status-button:focus,
  .button-attach:focus,
  .feedbo-simple-button:focus {
    color: #494949;
    border-color: #494949;
  }
  .ant-dropdown-menu-item:hover,
  .ant-dropdown-menu-submenu-title:hover,
  .ant-select-dropdown-menu-item:hover:not(.ant-select-dropdown-menu-item-disabled),
  .ant-select-dropdown-menu-item-active:not(.ant-select-dropdown-menu-item-disabled) {
    background-color: #f5f7fa;
  }

  .mobile-head-menu {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 32px !important;
    border-bottom: 1px solid #e8e8e8;
  }
  .mobile-head-menu-select .ant-select-selection--single {
    height: 35px;
  }
  .mobile-head-menu-select
    .ant-select-selection--single
    .ant-select-selection__rendered {
    line-height: 33px;
  }
  .mobile-head-menu-select.ant-select-open .ant-select-selection,
  .mobile-head-menu-select.ant-select-focused .ant-select-selection {
    box-shadow: none;
    border-color: #494949;
  }
  .modal-post-content .ant-modal-close {
    display: none;
  }
  @media only screen and (max-width: 600px) {
    .modal-post-content-anonymous .ant-modal-close {
      display: block;
    }
    .mobile-head-menu {
      padding: 10px 15px !important;
    }
  }
}
</style>
