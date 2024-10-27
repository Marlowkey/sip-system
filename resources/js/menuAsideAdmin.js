import {
    mdiMonitor,
    mdiAccountGroup
  } from '@mdi/js'


  export default [
      {
        route: 'home',
        icon: mdiMonitor,
        label: 'Dashboard'
      },
      {
        route: 'users.index',
        icon: mdiAccountGroup ,
        label: 'Users'
      },
  ]
