# React Router Setup Guide

## ✅ Routes Conversion Complete!

The Vue Router routes have been successfully converted to React Router v6 with all necessary pages created.

## 📦 Installation

First, install the new dependencies:

```bash
pnpm install
```

This will install:
- `react-router-dom@^6.28.0` - React Router v6
- `@types/react-router-dom@^5.3.3` - TypeScript types

## 🗺️ Routes Structure

### Vue Router (Old)
```javascript
// apps/src/routes.js
const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/board/:id', name: 'Board', component: Board, children: [...] },
  { path: '/new-board', name: 'NewBoard', component: NewBoard },
  { path: '/private', name: 'Private', component: PrivateBoard },
  { path: '/not-found', name: 'NotFound', component: NotFoundBoard },
];
```

### React Router (New)
```typescript
// src/routes.tsx
const routes: RouteObject[] = [
  { path: '/', element: <Home /> },
  { path: '/board/:id', element: <Board />, children: [...] },
  { path: '/new-board', element: <NewBoard /> },
  { path: '/private', element: <PrivateBoard /> },
  { path: '/not-found', element: <NotFoundBoard /> },
  { path: '*', element: <NotFoundBoard /> }, // Catch-all for 404
];
```

## 📄 Created Files

### 1. **`src/routes.tsx`**
Main routes configuration using React Router v6's `createBrowserRouter`.

### 2. **`src/app.tsx`** (Updated)
Root app component using `RouterProvider` to render routes.

### 3. **`src/pages/board/index.tsx`**
Board detail page with:
- Dynamic route parameter (`:id`)
- Header with logo and account
- Board content area
- Nested route support with `<Outlet />`

### 4. **`src/pages/board/comment.tsx`**
Nested comment route that displays under the board:
- Accessible at `/board/:id/:postSlug`
- Receives both `id` and `postSlug` params

### 5. **`src/pages/private/index.tsx`**
Private board access denied page with:
- Lock icon
- Clear messaging
- Navigation back to home

### 6. **`src/pages/not-found/index.tsx`**
404 page with:
- Large 404 display
- Helpful message
- Navigation back to home

## 🚀 Usage

### Basic Navigation

#### Using Links
```tsx
import { Link } from 'react-router-dom';

<Link to="/new-board">Create Board</Link>
<Link to="/board/my-board">Go to Board</Link>
```

#### Using Navigate Hook
```tsx
import { useNavigate } from 'react-router-dom';

const navigate = useNavigate();

const handleClick = () => {
  navigate('/board/my-board');
};
```

### Accessing Route Parameters

```tsx
import { useParams } from 'react-router-dom';

const { id, postSlug } = useParams<{ id: string; postSlug: string }>();
```

### Programmatic Navigation

```tsx
import { useNavigate } from 'react-router-dom';

const navigate = useNavigate();

// Navigate to a route
navigate('/new-board');

// Navigate with state
navigate('/board/my-board', { state: { from: 'home' } });

// Go back
navigate(-1);

// Replace current entry
navigate('/new-board', { replace: true });
```

## 🔄 Migration from Vue Router

### Vue Router → React Router Equivalents

| Vue Router | React Router v6 |
|------------|-----------------|
| `this.$route.params.id` | `useParams()` |
| `this.$router.push('/path')` | `navigate('/path')` |
| `<router-link :to="...">` | `<Link to="...">` |
| `<router-view />` | `<Outlet />` |
| `this.$route.query` | `useSearchParams()` |
| `beforeRouteEnter` | Component logic in `useEffect` |

### Example Conversions

#### Vue (Old):
```vue
<router-link :to="{ name: 'Board', params: { id: boardId } }">
  Go to Board
</router-link>

<script>
export default {
  methods: {
    goToBoard() {
      this.$router.push({ name: 'Board', params: { id: this.boardId } });
    }
  }
}
</script>
```

#### React (New):
```tsx
import { Link, useNavigate } from 'react-router-dom';

<Link to={`/board/${boardId}`}>Go to Board</Link>

const navigate = useNavigate();
const goToBoard = () => {
  navigate(`/board/${boardId}`);
};
```

## 🎯 Nested Routes

The Board component supports nested routes (like Comments):

```tsx
// In routes.tsx
{
  path: '/board/:id',
  element: <Board />,
  children: [
    {
      path: ':postSlug',
      element: <Comment />,
    },
  ],
}

// In Board component
import { Outlet } from 'react-router-dom';

return (
  <div>
    {/* Board content */}
    <Outlet /> {/* Nested routes render here */}
  </div>
);
```

### Accessing Nested Route:
- Board only: `/board/my-board`
- Board with comment: `/board/my-board/my-post-slug`

## 🔧 Advanced Features

### Protected Routes

You can create protected routes by wrapping them:

```tsx
// src/components/ProtectedRoute.tsx
import { Navigate } from 'react-router-dom';

function ProtectedRoute({ children, user }) {
  if (!user) {
    return <Navigate to="/private" replace />;
  }
  return children;
}

// In routes.tsx
{
  path: '/board/:id',
  element: (
    <ProtectedRoute user={user}>
      <Board />
    </ProtectedRoute>
  ),
}
```

### Route Loaders (React Router v6.4+)

For data fetching before route renders:

```tsx
{
  path: '/board/:id',
  element: <Board />,
  loader: async ({ params }) => {
    const response = await fetch(`/api/boards/${params.id}`);
    return response.json();
  },
}
```

### Error Boundaries

```tsx
{
  path: '/board/:id',
  element: <Board />,
  errorElement: <ErrorPage />,
}
```

## 📱 Hash Router (Optional)

If you need hash-based routing (for WordPress compatibility):

```tsx
import { createHashRouter } from 'react-router-dom';

export const router = createHashRouter(routes);
```

This will use `/#/board/...` instead of `/board/...`

## 🐛 Troubleshooting

### Issue: Routes not working
**Solution:** Make sure you've installed dependencies:
```bash
pnpm install
```

### Issue: TypeScript errors
**Solution:** Restart TypeScript server in VS Code:
- `Cmd/Ctrl + Shift + P`
- Type "TypeScript: Restart TS Server"

### Issue: 404 on refresh
**Solution:** 
- For hash router, no server config needed
- For browser router, configure your server to always serve `index.html`

## 🎨 Next Steps

1. **Install dependencies:**
   ```bash
   pnpm install
   ```

2. **Start dev server:**
   ```bash
   pnpm dev
   ```

3. **Implement missing components:**
   - Menu component
   - ListPost component
   - NavBar component
   - BoardManage component
   - Full Comment component

4. **Connect to API:**
   - Replace mock data with real API calls
   - Implement data fetching hooks
   - Add error handling

5. **Add features:**
   - Loading states
   - Error boundaries
   - Protected routes
   - Route transitions

## 📚 Resources

- [React Router v6 Documentation](https://reactrouter.com/)
- [React Router v6 Migration Guide](https://reactrouter.com/en/main/upgrading/v5)
- [React Router Examples](https://github.com/remix-run/react-router/tree/main/examples)

---

**Note:** The router is fully configured and ready to use. All pages are created with placeholder content. You can now start implementing the actual functionality for each page!

