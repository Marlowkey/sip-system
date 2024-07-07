import {
    mdiAccountCircle,
    mdiMonitor,
    mdiLock,
    mdiAlertCircle,
    mdiSquareEditOutline,
    mdiTable,
    mdiAccountFile,
    mdiViewList,
    mdiTelevisionGuide,
    mdiResponsive,
    mdiPalette,
  } from '@mdi/js'


  export default [
      {
        route: 'home',
        icon: mdiMonitor,
        label: 'Home'
      },
      {
        route: 'documents.index',
        icon: mdiAccountFile,
        label: 'Documents'
      },
      {
        route: 'tables',
        label: 'Attendance',
        icon: mdiTable
      },
      {
        route: 'forms',
        label: 'Report',
        icon: mdiSquareEditOutline
      },
  ]
