
import Base from './Base';
import Auth from './components/Auth';
import App from './components/App';
import Contacts from './components/App/Contacts';
import Chats from './components/App/Chats';
import Settings from './components/App/Settings';
import Chat from './components/App/Chats/Chat';

export default [
    {
        name: "auth",
        path: '/auth',
        component: Auth,
        props: true,
    },
    {
        name: "app",
        path: '/app',
        component: App,
        props: true,
        children: [
            {
                name: "app_contacts",
                path: '/app/contacts',
                component: Contacts,
                props: true,
            },
            {
                name: "app_chats",
                path: '/app/chats',
                component: Chats,
                props: true,
            },
            {
                name: "app_settings",
                path: '/app/settings',
                component: Settings,
                props: true,
            },
            {
                name: "app_chat",
                path: '/app/chat/:id',
                component: Chat,
                props: true,
            },
        ]
    }
]