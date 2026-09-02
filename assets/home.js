const Home = {
    tellDeveloper: (event) => {
        event.preventDefault();
        const form = event.target;
        const email = form.email.value;

        if (!email) {
            return;
        }

        fetch('/api/v1/email', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email })
        })
            .then((response) => {
                form.reset();
                form.classList.add('hidden');

                if (response.status !== 201) {
                    document.getElementById('notify-message-error').classList.remove('hidden');
                } else {
                    document.getElementById('notify-message-success').classList.remove('hidden');
                }
            })
    },
};

globalThis.Home = Home;
export default Home;
