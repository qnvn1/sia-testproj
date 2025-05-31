document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const getRandomImageBtn = document.getElementById('getRandomImageBtn');
    const randomFoodImage = document.getElementById('randomFoodImage');
    const getAdviceBtn = document.getElementById('getAdviceBtn');
    const randomAdvice = document.getElementById('randomAdvice');
    const mealSearchBtn = document.getElementById('mealSearchBtn');
    const mealSearchInput = document.getElementById('mealSearchInput');
    const mealResults = document.getElementById('mealResults');
    const getExercisesBtn = document.getElementById('getExercisesBtn');
    const muscleSelect = document.getElementById('muscleSelect');
    const exerciseResults = document.getElementById('exerciseResults');
    const getExercisesByTypeBtn = document.getElementById('getExercisesByTypeBtn');
    const exerciseTypeSelect = document.getElementById('exerciseTypeSelect');
    const getAdviceByIdBtn = document.getElementById('getAdviceByIdBtn');
    const adviceIdInput = document.getElementById('adviceIdInput');
    const adviceResults = document.getElementById('adviceResults');
    const saveMealForm = document.getElementById('saveMealForm');
    const saveMealSection = document.getElementById('saveMealSection');
    const saveMealMessage = document.getElementById('saveMealMessage');
    
    // Base URL for API
    const BASE_URL = 'http://localhost:8000'; // Change this to your backend URL
    
    // Check if user is logged in
    let isLoggedIn = false;
    let authToken = null;
    
    // Event Listeners
    loginBtn.addEventListener('click', () => loginModal.show());
    registerBtn.addEventListener('click', () => registerModal.show());
    
    getRandomImageBtn.addEventListener('click', getRandomFoodImage);
    getAdviceBtn.addEventListener('click', getRandomAdvice);
    mealSearchBtn.addEventListener('click', searchMeals);
    getExercisesBtn.addEventListener('click', getExercisesByMuscle);
    getExercisesByTypeBtn.addEventListener('click', getExercisesByType);
    getAdviceByIdBtn.addEventListener('click', getAdviceById);
    
    if (saveMealForm) {
        saveMealForm.addEventListener('submit', saveMeal);
    }
    
    // Forms
    loginForm.addEventListener('submit', handleLogin);
    registerForm.addEventListener('submit', handleRegister);
    
    // Initialize with some data
    getRandomFoodImage();
    getRandomAdvice();
    
    // Functions
    async function handleLogin(e) {
        e.preventDefault();
        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;
        
        try {
            const response = await fetch(`${BASE_URL}/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email,
                    password
                })
            });
            
            const data = await response.json();
            
            if (response.ok) {
                authToken = data.token;
                isLoggedIn = true;
                updateAuthUI();
                loginModal.hide();
                document.getElementById('loginMessage').textContent = '';
                showMessage('success', 'Logged in successfully!');
            } else {
                document.getElementById('loginMessage').textContent = data.message || 'Login failed';
            }
        } catch (error) {
            document.getElementById('loginMessage').textContent = 'An error occurred during login';
            console.error('Login error:', error);
        }
    }
    
    async function handleRegister(e) {
        e.preventDefault();
        const name = document.getElementById('registerName').value;
        const email = document.getElementById('registerEmail').value;
        const password = document.getElementById('registerPassword').value;
        const passwordConfirm = document.getElementById('registerPasswordConfirm').value;
        
        if (password !== passwordConfirm) {
            document.getElementById('registerMessage').textContent = 'Passwords do not match';
            return;
        }
        
        try {
            const response = await fetch(`${BASE_URL}/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    email,
                    password,
                    password_confirmation: passwordConfirm
                })
            });
            
            const data = await response.json();
            
            if (response.ok) {
                registerModal.hide();
                document.getElementById('registerMessage').textContent = '';
                showMessage('success', 'Registration successful! Please login.');
                loginModal.show();
            } else {
                document.getElementById('registerMessage').textContent = data.message || 'Registration failed';
            }
        } catch (error) {
            document.getElementById('registerMessage').textContent = 'An error occurred during registration';
            console.error('Registration error:', error);
        }
    }
    
    function updateAuthUI() {
        if (isLoggedIn) {
            loginBtn.style.display = 'none';
            registerBtn.style.display = 'none';
            saveMealSection.style.display = 'block';
        } else {
            loginBtn.style.display = 'block';
            registerBtn.style.display = 'block';
            saveMealSection.style.display = 'none';
        }
    }
    
    async function getRandomFoodImage() {
        try {
            const response = await fetch(`${BASE_URL}/meal/foodish/random`);
            if (response.ok) {
                const blob = await response.blob();
                const imageUrl = URL.createObjectURL(blob);
                randomFoodImage.src = imageUrl;
            } else {
                throw new Error('Failed to fetch image');
            }
        } catch (error) {
            console.error('Error fetching random food image:', error);
            showMessage('danger', 'Failed to load random food image');
        }
    }
    
    async function getRandomAdvice() {
        try {
            const response = await fetch(`${BASE_URL}/meals/random-advice`);
            const data = await response.json();
            
            if (response.ok) {
                randomAdvice.innerHTML = `
                    <div class="alert alert-info">
                        <p class="mb-0"><strong>Advice #${data.slip.id}</strong>: ${data.slip.advice}</p>
                    </div>
                `;
            } else {
                throw new Error(data.error || 'Failed to fetch advice');
            }
        } catch (error) {
            console.error('Error fetching random advice:', error);
            randomAdvice.innerHTML = `
                <div class="alert alert-danger">
                    <p class="mb-0">Failed to load advice. Please try again.</p>
                </div>
            `;
        }
    }
    
    async function searchMeals() {
        const query = mealSearchInput.value.trim() || 'pasta';
        
        try {
            const response = await fetch(`${BASE_URL}/meals/search?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            
            if (response.ok) {
                displayMealResults(data);
            } else {
                throw new Error(data.error || 'Failed to search meals');
            }
        } catch (error) {
            console.error('Error searching meals:', error);
            mealResults.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        Failed to search meals. Please try again.
                    </div>
                </div>
            `;
        }
    }
    
    function displayMealResults(meals) {
        if (!meals || meals.length === 0) {
            mealResults.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-warning">
                        No meals found. Try a different search term.
                    </div>
                </div>
            `;
            return;
        }
        
        mealResults.innerHTML = '';
        
        meals.forEach(meal => {
            const mealCard = document.createElement('div');
            mealCard.className = 'col-md-4 mb-4';
            mealCard.innerHTML = `
                <div class="card h-100">
                    <img src="${meal.strMealThumb}" class="card-img-top food-image" alt="${meal.strMeal}">
                    <div class="card-body">
                        <h5 class="card-title">${meal.strMeal}</h5>
                        <p class="card-text">${meal.strCategory} - ${meal.strArea}</p>
                        <button class="btn btn-outline-primary btn-sm view-meal-btn" data-id="${meal.idMeal}">
                            View Details
                        </button>
                        ${isLoggedIn ? `
                        <button class="btn btn-outline-success btn-sm save-meal-btn" 
                                data-id="${meal.idMeal}" 
                                data-name="${meal.strMeal}">
                            Save to Plan
                        </button>
                        ` : ''}
                    </div>
                </div>
            `;
            mealResults.appendChild(mealCard);
        });
        
        // Add event listeners to the new buttons
        document.querySelectorAll('.view-meal-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const mealId = btn.getAttribute('data-id');
                viewMealDetails(mealId);
            });
        });
        
        if (isLoggedIn) {
            document.querySelectorAll('.save-meal-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const mealId = btn.getAttribute('data-id');
                    const mealName = btn.getAttribute('data-name');
                    document.getElementById('mealId').value = mealId;
                    document.getElementById('mealName').value = mealName;
                    // Scroll to the save form
                    saveMealSection.scrollIntoView({ behavior: 'smooth' });
                });
            });
        }
    }
    
    async function viewMealDetails(mealId) {
        try {
            // In a real app, you would fetch detailed meal information here
            alert(`Viewing details for meal ID: ${mealId}\n\nIn a complete implementation, this would show more details about the meal.`);
        } catch (error) {
            console.error('Error viewing meal details:', error);
            showMessage('danger', 'Failed to load meal details');
        }
    }
    
    async function getExercisesByMuscle() {
        const muscle = muscleSelect.value;
        
        try {
            const response = await fetch(`${BASE_URL}/meals/exercises?muscle=${muscle}`);
            const data = await response.json();
            
            if (response.ok) {
                displayExerciseResults(data, `${muscle} exercises`);
            } else {
                throw new Error(data.error || 'Failed to fetch exercises');
            }
        } catch (error) {
            console.error('Error fetching exercises:', error);
            exerciseResults.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        Failed to load exercises. Please try again.
                    </div>
                </div>
            `;
        }
    }
    
    async function getExercisesByType() {
        const type = exerciseTypeSelect.value;
        
        try {
            const response = await fetch(`${BASE_URL}/meal/exercises/${type}`);
            const data = await response.json();
            
            if (response.ok) {
                displayExerciseResults(data, `${type} exercises`);
            } else {
                throw new Error(data.error || 'Failed to fetch exercises');
            }
        } catch (error) {
            console.error('Error fetching exercises by type:', error);
            exerciseResults.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        Failed to load exercises. Please try again.
                    </div>
                </div>
            `;
        }
    }
    
    function displayExerciseResults(exercises, title) {
        if (!exercises || exercises.length === 0) {
            exerciseResults.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-warning">
                        No exercises found. Try a different muscle or type.
                    </div>
                </div>
            `;
            return;
        }
        
        exerciseResults.innerHTML = `
            <div class="col-12">
                <h4>${title}</h4>
            </div>
        `;
        
        exercises.forEach(exercise => {
            const exerciseCard = document.createElement('div');
            exerciseCard.className = 'col-md-6 mb-4';
            exerciseCard.innerHTML = `
                <div class="card exercise-card h-100">
                    <div class="card-body">
                        <h5 class="card-title">${exercise.name}</h5>
                        <p class="card-text">
                            <strong>Muscle:</strong> ${exercise.muscle}<br>
                            <strong>Type:</strong> ${exercise.type}<br>
                            <strong>Equipment:</strong> ${exercise.equipment}
                        </p>
                        <p class="card-text"><small class="text-muted">${exercise.instructions || 'No instructions provided.'}</small></p>
                    </div>
                </div>
            `;
            exerciseResults.appendChild(exerciseCard);
        });
    }
    
    async function getAdviceById() {
        const adviceId = adviceIdInput.value.trim();
        
        if (!adviceId) {
            showMessage('warning', 'Please enter an advice ID');
            return;
        }
        
        try {
            const response = await fetch(`${BASE_URL}/meals/advice/${adviceId}`);
            const data = await response.json();
            
            if (response.ok) {
                adviceResults.innerHTML = `
                    <div class="card mb-3 advice-card">
                        <div class="card-body">
                            <h5>Advice #${data.slip.id}</h5>
                            <p>${data.slip.advice}</p>
                        </div>
                    </div>
                `;
            } else {
                throw new Error(data.error || 'Failed to fetch advice');
            }
        } catch (error) {
            console.error('Error fetching advice by ID:', error);
            adviceResults.innerHTML = `
                <div class="alert alert-danger">
                    <p class="mb-0">Failed to load advice. Please try again.</p>
                </div>
            `;
        }
    }
    
    async function saveMeal(e) {
        e.preventDefault();
        
        if (!isLoggedIn) {
            showMessage('warning', 'Please login to save meals');
            return;
        }
        
        const mealId = document.getElementById('mealId').value;
        const mealName = document.getElementById('mealName').value;
        const date = document.getElementById('mealDate').value;
        
        try {
            const response = await fetch(`${BASE_URL}/meals/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${authToken}`
                },
                body: JSON.stringify({
                    meal_id: mealId,
                    meal_name: mealName,
                    date: date
                })
            });
            
            const data = await response.json();
            
            if (response.ok) {
                saveMealMessage.innerHTML = `
                    <div class="alert alert-success">
                        Meal saved successfully!
                    </div>
                `;
                // Reset form
                saveMealForm.reset();
            } else {
                throw new Error(data.error || 'Failed to save meal');
            }
        } catch (error) {
            console.error('Error saving meal:', error);
            saveMealMessage.innerHTML = `
                <div class="alert alert-danger">
                    Failed to save meal. Please try again.
                </div>
            `;
        }
    }
    
    function showMessage(type, text) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `alert alert-${type} fixed-top mx-auto mt-3`;
        messageDiv.style.width = '300px';
        messageDiv.style.zIndex = '1100';
        messageDiv.textContent = text;
        
        document.body.appendChild(messageDiv);
        
        setTimeout(() => {
            messageDiv.remove();
        }, 3000);
    }
    
    // Initialize
    updateAuthUI();
});