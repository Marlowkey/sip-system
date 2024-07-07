import {
    mdiAccountCircle,
    mdiMonitor,
    mdiLock,
    mdiAlertCircle,
    mdiSquareEditOutline,
    mdiTable,
    mdiViewList,
    mdiTelevisionGuide,
    mdiResponsive,
    mdiPalette,
    mdiAccountFile
  } from '@mdi/js'


  export default [
      {
        route: 'home',
        icon: mdiMonitor,
        label: 'Dashboard'
      },
      {
        route: 'documents.index',
        icon: mdiAccountFile,
        label: 'Document Submission'
      },
      {
        route: 'tables',
        label: 'Tables',
        icon: mdiTable
      },
      {
        route: 'forms',
        label: 'Forms',
        icon: mdiSquareEditOutline
      },
      {
        route: 'error',
        label: 'Error',
        icon: mdiAlertCircle
      }
  ]
