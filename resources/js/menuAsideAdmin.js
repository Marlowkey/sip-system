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
        route: 'htes.index',
        icon: mdiDomain   ,
        label: 'Host Training Establishments'
      },
      {
        route: 'schoolyears.index',
        icon: mdiSchoolOutline   ,
        label: 'School Year'
      },
  ]
