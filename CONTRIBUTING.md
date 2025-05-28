# Contributing to MyNewProject

Thank you for considering contributing to MyNewProject! This document outlines the guidelines and processes for contributing to this project.

## Code of Conduct

This project adheres to a Code of Conduct that all contributors are expected to follow. Please read [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md) before contributing.

## How to Contribute

1. **Fork the Repository**
   - Fork the repository to your GitHub account
   - Clone your fork locally

2. **Create a Branch**
   - Create a new branch for your changes
   - Use a descriptive name: `feature/your-feature` or `fix/your-fix`

3. **Make Your Changes**
   - Follow the coding standards (PSR-12)
   - Write meaningful commit messages
   - Add tests for new features
   - Update documentation as needed

4. **Test Your Changes**
   - Run the test suite: `php artisan test`
   - Ensure all tests pass
   - Add new tests if needed

5. **Submit a Pull Request**
   - Push your changes to your fork
   - Create a pull request from your branch to our main branch
   - Describe your changes in detail
   - Reference any related issues

## Development Setup

1. **Prerequisites**
   - PHP >= 8.1
   - Composer
   - Node.js & NPM
   - MySQL

2. **Installation**
   ```bash
   composer install
   npm install
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

## Coding Standards

- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Comment complex code sections
- Keep functions small and focused
- Use type hints and return types

## Git Commit Guidelines

Format: `type(scope): subject`

Types:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, etc)
- `refactor`: Code refactoring
- `test`: Adding tests
- `chore`: Maintenance tasks

Example:
```
feat(auth): add social login feature
fix(database): resolve migration issue
docs(api): update API documentation
```

## Testing

- Write unit tests for new features
- Maintain test coverage
- Run tests before submitting PR
- Document test cases

## Documentation

- Update README.md for major changes
- Document new features
- Keep API documentation current
- Add inline documentation for complex logic

## Questions or Problems?

- Open an issue for bugs
- Use discussions for questions
- Tag issues appropriately 