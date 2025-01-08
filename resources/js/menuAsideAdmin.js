import {
    mdiMonitor,
    mdiAccountGroup,
    mdiAccountCheck,
    mdiDomain,
    mdiSchoolOutline
  } from '@mdi/js'


  export default [
      {
        route: 'home',
        icon: mdiMonitor,
        label: 'Home'
      },
      {
        route: 'users-student.index',
        icon: mdiAccountGroup ,
        label: 'Student'
      },
      {
        route: 'users-coordinator.index',
        icon: mdiAccountCheck  ,
        label: 'Coordinator'
      },
      {
        route: 'schoolyears.index',
        icon: mdiSchoolOutline   ,
        label: 'School Year'
      },
  ]
