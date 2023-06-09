window.addEventListener('load', function() {
  createStyleTag();
  createDivContainer();
  createIframeMask();
  var element = findMe('script', 'src', 'widget.js');
  var index = element.indexOf(".js");
  var siteUrl = element.substr(0, index);
  let url = "";
  let buttonFeedback = document.querySelector('[data-feedbo-id]');
  if(buttonFeedback != undefined) {
    url = buttonFeedback.getAttribute("data-feedbo-id");
    createIframe(siteUrl+'/'+url);
    buttonFeedback.addEventListener("click", function(event) {
      event.preventDefault();
      showFrameContent();
    });
  }
  if(typeof vote !== 'undefined') {
    var request = vote.selector.substring(0, 1);
    url = vote.id;
    createIframe(siteUrl+'/'+url);
    if(request == "."){
      var selector = vote.selector.substring(1);
      var a = document.getElementsByClassName(`${selector}`);
      for (i = 0; i < a.length; i++) {
          a[i].addEventListener("click", showFrameContent);
      }
    }
    else {
      if(request == "#") {
        var selector = vote.selector.substring(1);
        document.getElementById(`${selector}`).addEventListener("click", showFrameContent);
      }     
      else {
        var a = document.getElementsByTagName(`${vote.selector}`);
        for (i = 0; i < a.length; i++) {
            a[i].addEventListener("click", showFrameContent);
        }
      }
    }
  }
  var modal = document.getElementById('feedbo_frame_cont');
  var frameMask = document.getElementById('feedbo_frame_mask');

  frameMask.addEventListener("click", function() {
    modal.classList.remove("feedbo-visible");
    modal.firstChild.classList.remove("feedbo-mask");
  });
});

function createStyleTag() {
  var css =  '.feedbo-bottom { bottom: -16px; }\n';
      css += '.feedbo-top { top: -16px; }\n';
      css += '.feedbo-left { left: -16px; }\n';
      css += '.feedbo-right { right: -16px; }\n';
      css += '.feedbo-center, .feedbo-vcenter { left: 50%; margin-left: -16px; }\n';
      css += '.feedbo-center, .feedbo-hcenter { top: 50%; margin-top: -16px; }\n';
      css += '.feedbo-frame-cont { pointer-events: none; border-radius: 4px; box-shadow: 0 0 1px rgba(99, 114, 130, 0.32), 0 8px 16px rgba(27, 39, 51, 0.08); background: #fff; border: none; position: fixed; top: -900em; z-index: 2147483647; width: 535px; height: 0;opacity: 0; will-change: height, margin-top, opacity; margin-top: -10px; transition: margin-top 0.15s ease-out, opacity 0.1s ease-out; }\n';
      css += '.feedbo-frame-mask { visibility: hidden; position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 1000; height: 100%; background-color: rgba(0,0,0,.45); transition: visibility 0.5s linear; }\n';
      css += '.feedbo-frame-mask.feedbo-mask { visibility: visible; }\n';
      css += '.feedbo-frame-cont.feedbo-visible { opacity: 1; pointer-events: auto; margin-top: 0px; }\n';
      css += '.feedbo-frame-cont.feedbo-visible.feedbo-bottom { transition: margin-top 0.15s ease-out, opacity 0.1s ease-out, height 0.3s ease-out; }\n';
      css += '.feedbo-frame { background: #fff; border: none; width: 100%; height: 100%; overflow: hidden; border-radius: 4px; position: relative; z-index: 2147483647; transition: height 0.3s ease-out; }\n';
      css += '.feedbo-frame-cont.feedbo-embed { position: static; margin-top: 0; }\n';
      css += '.feedbo-notransition { transition: none !important }\n';
      css += '[data-feedbo-id] { height: 32px; line-height: 32px; font-weight: 400; white-space: nowrap; text-align: center; padding: 0 15px; font-size: 14px; border-radius: 4px; color: #fff; background-color: #1890ff; border: 1px solid #d9d9d9; box-shadow: 0 2px 0 rgba(0,0,0,.015); cursor: pointer; transition: all .3s cubic-bezier(.645,.045,.355,1); }\n';
      css += '[data-feedbo-id]:focus { outline: none; }\n';
      css += '[data-feedbo-id]:focus,[data-feedbo-id]:hover { color: #fff; background-color: #40a9ff; border-color: #40a9ff; }\n';
      css += '@media only screen and (max-width: 600px) {\n.feedbo-frame-cont { width: 100%;}\n}\n';
      var head = document.head || document.getElementsByTagName('head')[0];
  style = document.createElement('style');
  style.setAttribute('id', 'feedbo_styles_cont');
  head.appendChild(style);
  style.type = 'text/css';
  if (style.styleSheet){
    // This is required for IE8 and below.
    style.styleSheet.cssText = css;
  } else {
    style.appendChild(document.createTextNode(css));
  }
}
function createDivContainer() {
  var divTag = document.createElement("div");
  divTag.style.height = "450px";
  divTag.style.left = "50%";
  divTag.style.top = "50%";
  divTag.style.marginTop = "-225px";
  divTag.style.marginLeft = "-250px";
  divTag.setAttribute('id', 'feedbo_frame_cont');
  divTag.setAttribute('class', 'feedbo-frame-cont feedbo-bottom');
  document.body.appendChild(divTag);
}
function createIframeMask() {
  var mask = document.createElement('div');
  mask.setAttribute('id', 'feedbo_frame_mask'); // assign an id
  mask.setAttribute('class', 'feedbo-frame-mask');
  document.getElementById("feedbo_frame_cont").appendChild(mask);
}
function createIframe(url) {
  var ifrm = document.createElement('iframe');
    ifrm.setAttribute('id', 'feedbo_frame'); // assign an id
    ifrm.setAttribute('class', 'feedbo-frame'); // assign an class
    // assign url
    ifrm.setAttribute('src', url);
    ifrm.setAttribute('sanbox', 'allow-same-origin allow-scripts allow-top-navigation allow-popups allow-forms allow-popups-to-escape-sandbox');
    ifrm.style.height = "450px";
    document.getElementById("feedbo_frame_cont").appendChild(ifrm);
}
function showFrameContent() {
  document.getElementById("feedbo_frame_mask").classList.add("feedbo-mask");
  document.getElementById("feedbo_frame_cont").classList.add("feedbo-visible");
  
}
function findMe(tag, attr, file) {
  var tags = document.getElementsByTagName(tag);
  var r = new RegExp(file + '$');
  for (var i = 0;i < tags.length;i++) {
    if (r.exec(tags[i][attr])) {
      return tags[i][attr];
    }
  }
};
