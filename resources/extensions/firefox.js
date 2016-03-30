var data = require("sdk/self").data;
var pageMod = require("sdk/page-mod");

pageMod.PageMod({
    include: "*.parts.dev",
    contentScriptFile: data.url("extension-report.js"),
    contentScriptWhen: "ready"
});

pageMod.PageMod({
    include: "*.mouser.com",
    contentScriptFile: data.url("content-script.js"),
    contentScriptWhen: "ready"
});

pageMod.PageMod({
    include: "*.digikey.com",
    contentScriptFile: data.url("content-script.js"),
    contentScriptWhen: "ready"
});
