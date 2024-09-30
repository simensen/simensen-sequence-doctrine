{ pkgs ? import <nixpkgs> {}}:

let
  configuredPkgs = {
    php = pkgs.php.withExtensions ({ all, enabled }: enabled ++ (with all; [ gnupg xdebug ]));
  };
in
  pkgs.mkShell {
    name = "simensen-sequence-doctrine";
    packages = [
      configuredPkgs.php
      configuredPkgs.php.packages.composer
      configuredPkgs.php.packages.phive
      pkgs.jetbrains.phpstorm
      pkgs.gnupg
      pkgs.yamllint
    ];
    shellHook =
      ''
        export PATH=$(pwd)/tools:$PATH
      '';
  }
