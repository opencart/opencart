require "sitegen"

tools = require "sitegen.tools"

sitegen.create_site =>
  @current_version = "0.0.12"
  @title = "SCSS Compiler in PHP"

  scssphp = tools.system_command "pscss < %s > %s", "css"
  build scssphp, "style.scss", "style/style.css"

  deploy_to "leaf@leafo.net", "www/scssphp/"

  add "docs/index.md"

