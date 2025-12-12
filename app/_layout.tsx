import { Stack } from "expo-router";

export default function RootLayout() {

  if (process.env.EXPO_PUBLIC_ENVIRONMENT === 'storybook') {
    return (
      <Stack>
        {/* default to the storybook route */}
        <Stack.Screen name="storybook" options={{ headerShown: false }} />

        {/* deny access to the index route */}
        <Stack.Protected guard={false}>
          <Stack.Screen name="index" />
        </Stack.Protected>
      </Stack>
    );
  }

  return (
    <Stack>
      {/* deny access to the storybook route */}
      <Stack.Protected guard={false}>
        <Stack.Screen name="storybook" />
      </Stack.Protected>
    </Stack>
  );
}
