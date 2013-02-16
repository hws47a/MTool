Mtool
=======

Mtool is a magento code-genarator which should help magento-developers with their daily tasks. It uses zend tool framework.

### Example
  
`zf create mage-module Namespace/ModuleName`
  
Will create all needed paths, files and configs.  
  
Or see how to create a custom module with CRUD using MTool [here](https://github.com/hws47a/MTool/wiki/Example)

My Fork Information
=======

My fork is an extended version of dankockerga's [MTool](https://github.com/dankocherga/MTool)  
Currently all changes is unstable and to use it you will need to switch to [develop](https://github.com/hws47a/MTool/tree/develop) branch  
  
### My Changes


Added:  
* `create mage-controller` - create controller by url path, `admin/examlple` creates admin controller, `example/index` - frontend
* `create mage-table-entity` - create model / resource model / collection files and config data
* `create mage-crud` - create controller, grid and edit data files, add needed data to layout
* `add-admin-layout mage-design` and `add-frontend-layout mage-design` - can add layouts for adminhtml and frontend

Fixed:  
* correct installer path when upgrade mage-module
  
### Documentation

See in the [Wiki](https://github.com/hws47a/MTool/wiki)
