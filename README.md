Hotfixes for Contao Core
========================

Backports / Fixes
-----------------

| abbr            | date       | description                                                                                               |
|:--------------- |:----------:|:----------------------------------------------------------------------------------------------------------|
| `dt`            | 2014-11-25 | backport the directory traversal issues (from upstream 3.2.16/3.3.7).                                     |
| `xss2`          | 2014-11-25 | backport the xss2 issue (from upstream 3.2.16/3.3.7).                                                     |
| `moo`           | 2014-02-18 | upgrade Mootools Core and Mootools More to 1.3.2                                                          |
| `xss`           | 2014-02-17 | backport potential XSS vulnerability from 2.10.2 into all lower versions                                  |
| `soa-hardening` | 2014-02-13 | backport hardenings related to Serialized object attack from 3.2.7/2.11.16                                |
| `soa`           | 2014-02-10 | backport hotfix for the Serialized object attack, see [#6695](https://github.com/contao/core/issues/6695) |


Naming convention
-----------------

Our patched version always start with `x.x.x-cca`, each backported fix is denoted by appending a dot and the short name of the fix.

The version `x.x.x-cca.soa.soa-hardening` for example means that the version contains the backports of the following fixes: `soa` and `soa-hardening`.

**Hint** not all versions contain all fixes. Some of them already contain some fixes from origin.

About "Current" and "Obsolete"
------------------------------

The tables below are listed either as current or obsolete. Current means they contain all known fixes.
The obsolete ones are versions that contain only partial fixes as they got built while the version was still in maintenance at upstream but
other commits were introduced along the security fix.
**You should never use versions listed under "Obsolete" in production.**

Contao 3.X
----------

###Current

| abbr                                  | last update | complete source                                                                                            | patchset                                                                                                     |
| :------------------------------------ |:-----------:| ----------------------------------------------------------------------------------------------------------:| ------------------------------------------------------------------------------------------------------------:|
| `3.3.6-cca.xss2.dt`                   | 2014-11-25  | [complete source](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-3.3.6)  | [patchset](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-3.3.6-patchset)  |
| `3.2.15-cca.xss2.dt`                  | 2014-11-25  | [complete source](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-3.2.15) | [patchset](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-3.2.15-patchset) |
| `3.1.5-cca.soa.soa-hardening.xss2.dt` | 2014-11-25  | [complete source](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-3.1.5)  | [patchset](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-3.1.5-patchset)  |
| `3.0.6-cca.soa.soa-hardening.xss2.dt` | 2014-11-25  | [complete source](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-3.0.6)  | [patchset](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-3.0.6-patchset)  |

###Obsolete

| abbr                                  | last update | complete source                                                                                           | patchset                                                                                                    |
| :------------------------------------ |:-----------:| ---------------------------------------------------------------------------------------------------------:| -----------------------------------------------------------------------------------------------------------:|
| `3.2.4-cca.soa.soa-hardening`         | 2014-02-18  | [complete source](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-3.2.4) | [patchset](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-3.2.4-patchset) |

Contao 2.X
----------

###Current

| abbr                                          | last update | complete source                                                                                             | patchset                                                                                                      |
| :-------------------------------------------- |:-----------:| -----------------------------------------------------------------------------------------------------------:| -------------------------------------------------------------------------------------------------------------:|
| `2.11.17-cca.xss2.dt`                         | 2014-11-25  | [complete source](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-2.11.17) | [patchset](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-2.11.17-patchset) |
| `2.10.4-cca.soa.soa-hardening.xss2.dt`        | 2014-11-25  | [complete source](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-2.10.4)  | [patchset](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-2.10.4-patchset)  |
| `2.9.5-cca.soa.soa-hardening.xss.moo.xss2.dt` | 2014-11-25  | [complete source](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-2.9.5)   | [patchset](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-2.9.5-patchset)   |

###Obsolete

| abbr                            | last update | complete source                                                                                              | patchset                                                                                                      |
| :------------------------------ |:-----------:| ------------------------------------------------------------------------------------------------------------:| -------------------------------------------------------------------------------------------------------------:|
| `2.11.13-cca.soa.soa-hardening` | 2014-02-18  | [complete source](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-2.11.13)  | [patchset](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-2.11.13-patchset) |


TYPOlight 2.X
-------------

###Current

| abbr                                          | last update | complete source                                                                                           | patchset                                                                                                    |
| :-------------------------------------------- |:-----------:| ---------------------------------------------------------------------------------------------------------:| -----------------------------------------------------------------------------------------------------------:|
| `2.8.4-cca.soa.soa-hardening.xss.moo.xss2.dt` | 2014-11-25  | [complete source](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-2.8.4) | [patchset](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-2.8.4-patchset) |
| `2.7.7-cca.soa.soa-hardening.xss.moo.xss2.dt` | 2014-11-25  | [complete source](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-2.7.7) | [patchset](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-2.7.7-patchset) |
| `2.6.8-cca.soa.soa-hardening.xss.moo.xss2.dt` | 2014-11-25  | [complete source](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-2.6.8) | [patchset](https://github.com/contao-community-alliance/contao-core-hotfix/tree/securityfix-2.6.8-patchset) |
