# Contributing to ZENDX Social Scheduler

Thank you for your interest in contributing! This document provides guidelines for contributing to the project.

## Getting Started

1. **Fork** the repository on GitHub
2. **Clone** your fork locally:
   ```bash
   git clone https://github.com/your-username/zendx-social-scheduler.git
   cd zendx-social-scheduler
   ```
3. **Create** a new branch for your feature:
   ```bash
   git checkout -b feature/your-feature-name
   ```

## Development Setup

```bash
# Copy example config files
cp config.example.php config.php
cp config/secrets.example.php config/secrets.php
cp .env.example .env

# Edit with your local settings
nano config.php
nano config/secrets.php
nano .env

# Set up database
mysql -u root -p < init_db.sql

# Visit http://localhost/setup.php to create admin
```

## Code Standards

### PHP Style

- Follow PSR-12 coding standards
- Use 4 spaces for indentation
- Use meaningful variable names
- Add comments for complex logic
- Use prepared statements for all database queries

### Naming Conventions

- **Functions**: `snake_case`
- **Classes**: `PascalCase`
- **Constants**: `UPPER_CASE`
- **Variables**: `camelCase` or `snake_case` (consistent within file)

### Documentation

- Add PHPDoc comments to functions and classes
- Document parameters and return types
- Add inline comments for non-obvious logic
- Keep README.md updated with new features

## Testing

Before submitting:

1. **Test locally** with various browsers
2. **Test database** operations
3. **Test OAuth flow** with at least one provider
4. **Test edge cases** (empty inputs, large uploads, etc.)
5. **Check for XSS** vulnerabilities in user inputs
6. **Verify error handling** works correctly

## Commit Messages

Use clear, descriptive commit messages:

```
feature: add support for Pinterest OAuth

- Implement PinterestProvider class
- Add OAuth redirect handler
- Update ProviderFactory to include Pinterest
- Update README with Pinterest setup instructions

Fixes #123
```

**Format**: `<type>: <subject>`

**Types**: 
- `feature` - New feature
- `fix` - Bug fix
- `docs` - Documentation only
- `style` - Code formatting
- `refactor` - Code refactoring
- `test` - Adding tests
- `chore` - Build, CI, dependencies

## Pull Request Process

1. **Update** your branch with latest main:
   ```bash
   git fetch upstream
   git rebase upstream/main
   ```

2. **Push** your branch to your fork:
   ```bash
   git push origin feature/your-feature-name
   ```

3. **Create** a Pull Request on GitHub with:
   - Clear title describing the change
   - Description of what changed and why
   - Reference to related issues (#123)
   - Screenshots for UI changes
   - Test results

4. **Address** review feedback:
   - Make requested changes
   - Push updates (no force-push)
   - Re-request review

## Types of Contributions

### New Features

- Discuss major features first (open an issue)
- Follow existing code patterns
- Add documentation
- Update README if applicable

### Bug Fixes

- Describe the bug clearly in PR
- Include steps to reproduce
- Show how your fix resolves it
- Add tests if possible

### Documentation

- Fix typos and clarify instructions
- Add missing configuration examples
- Improve troubleshooting section
- Add API documentation

### Performance

- Benchmark before and after
- Include performance metrics
- Explain trade-offs

## Adding New Social Platforms

1. **Create Provider Class**: `providers/NewPlatformProvider.php`
   ```php
   <?php
   require_once 'ProviderInterface.php';
   
   class NewPlatformProvider implements ProviderInterface {
       // Implement required methods
   }
   ```

2. **Update ProviderFactory**: Add case in `providerFor()`

3. **Create OAuth Handler**: `providers/newplatform_oauth.php`

4. **Create Callback Handler**: `providers/newplatform_callback.php`

5. **Update Database**: Add config if needed

6. **Update README**: OAuth setup instructions

7. **Test**: Verify OAuth flow works

## Reporting Issues

Before reporting:
- Check if issue already exists
- Reproduce with clean database
- Note PHP and MySQL versions
- Include error messages

**Report template**:
```markdown
## Description
Brief description of the issue

## Steps to Reproduce
1. Login as admin
2. Go to Dashboard
3. Click "Create Post"
4. ...

## Expected Behavior
What should happen

## Actual Behavior
What actually happened

## Environment
- PHP Version: 7.4
- MySQL Version: 5.7
- Browser: Chrome 90
- OS: Ubuntu 20.04

## Error Message
[Full error message if applicable]
```

## Legal

- By contributing, you agree your code can be used under the project's license
- You represent you have the right to submit the code
- Your contributions become part of the project's history

## Questions?

- Open an issue for questions
- Check existing issues for answers
- Review documentation first

## Recognition

Contributors will be listed in:
- CONTRIBUTORS.md
- Commit history
- Release notes

Thank you for contributing! 🎉
