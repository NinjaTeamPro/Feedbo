// Import Vue
import Vue from "vue";
import VueRouter from "vue-router";

// Import Vue App, routes, store
import App from "./App";
import routes from "./routes";
import store from "./store/store";
Vue.use(VueRouter);
// Import PerfectScrollbar
import PerfectScrollbar from "vue2-perfect-scrollbar";
import "vue2-perfect-scrollbar/dist/vue2-perfect-scrollbar.css";
Vue.use(PerfectScrollbar);
//Import Ant design vue
import VueClipboards from "vue-clipboards";
import "ant-design-vue/dist/antd.css";
import {
  Button,
  Affix,
  Icon,
  Mentions,
  Switch,
  Modal,
  Form,
  Timeline,
  Input,
  Avatar,
  Menu,
  Comment,
  Tooltip,
  Spin,
  Radio,
  List,
  Tabs,
  Checkbox,
  Upload,
  message,
  Popover,
  Popconfirm,
  Badge,
  Tree,
  Select,
  FormModel,
  Skeleton,
  Dropdown,
  Row,
  Col,
  Layout,
  Collapse,
  Drawer,
} from "ant-design-vue";
Vue.use(Button);
Vue.use(Icon);
Vue.use(Modal);
Vue.use(Form);
Vue.use(VueRouter);
Vue.use(Input);
Vue.use(Avatar);
Vue.use(Menu);
Vue.use(List);
Vue.use(Comment);
Vue.use(Tooltip);
Vue.use(Tabs);
Vue.use(Upload);
Vue.use(Popover);
Vue.use(Select);
Vue.use(FormModel);
Vue.use(Skeleton);
Vue.use(Tree);
Vue.use(Badge);
Vue.use(Popconfirm);
Vue.use(Radio);
Vue.use(Checkbox);
Vue.use(Spin);
Vue.use(Dropdown);
Vue.use(VueClipboards);
Vue.use(Row);
Vue.use(Col);
Vue.use(Layout);
Vue.use(Collapse);
Vue.use(Timeline);
Vue.use(Drawer);
Vue.use(Affix);
Vue.use(Switch);
Vue.use(Mentions);
Vue.prototype.$confirm = Modal.confirm;
Vue.prototype.$message = message;
// Configure router
const router = new VueRouter({
  routes,
  linkActiveClass: "active",
  mode: "history",
});

router.afterEach(() => {
  document.querySelector("body").classList.remove("body-board");
});

new Vue({
  store,
  el: "#app",
  render: (h) => h(App),
  router,
});
